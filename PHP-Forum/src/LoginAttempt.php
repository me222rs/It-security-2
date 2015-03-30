<?php

/**
 * Provides everything needed to manage and use a login queue.
 * The login queue will store each login attempt in a database table
 * and process the entries one at at time, with a delay between
 * each one. The idea here is to make brute-force attacks on the
 * login system impractically slow.
 *
 * @author Atli Jonsson (http://www.dreamincode.net/forums/user/391059-atli/)
 * @date 2013-08-11
 * @modified 2013-08-22 Split into per-user queuing, added max queue size and
 *                      added the single IP restriction.
 */
class LoginAttempt
{
    /**
     * @var int The number of milliseconds to sleep between login attempts.
     */
    const ATTEMPT_DELAY = 1000;

    /**
     * @var int The number of milliseconds before an unchecked attempt is
     *          considered dead.
     *
     */
    const ATTEMPT_EXPIRATION_TIMEOUT = 5000;

    /**
     * @var int Number of queued attempts allowed per user.
     */
    const MAX_PER_USER = 5;

    /**
     * @var int Number of queued attempts allowed overall.
     */
    const MAX_OVERALL = 30;

    /**
     * The ID assigned to this attempt in the database.
     *
     * @var int
     */
    private $attemptID;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * After the login has been validated, this attribute will hold the
     * result. Subsequent calls to isValid will return this value, rather
     * that try to validate it again.
     *
     * @var bool
     */
    private $isLoginValid;

    /**
     * An open PDO instance.
     *
     * @var PDO
     */
    private $pdo;

    /**
     * Stores the statement used to check whether the attempt is ready to be processed.
     * As it may be used multiple times per attempt, it makes sense not to initialize
     * it each ready check.
     *
     * @var PDOStatement
     */
    private $readyCheckStatement;

    /**
     * The statement used to update the attempt entry in the database on
     * each isReady call.
     *
     * @var PDOStatement
     */
    private $checkUpdateStatement;

    /**
     * Creates a login attempt and queues it.
     *
     * @param string $username
     * @param string $password
     * @var \PDO $pdo
     * @throws Exception
     */
    public function __construct($username, $password, \PDO $pdo)
    {
        $this->pdo = $pdo;
        if ($this->pdo->getAttribute(PDO::ATTR_ERRMODE) !== PDO::ERRMODE_EXCEPTION) {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        $this->username = $username;
        $this->password = $password;

        if (!$this->isQueueSizeExceeded()) {
            $this->addToQueue();
        }
        else {
            throw new Exception("Queue size has been exceeded.", 503);
        }
    }

    /**
     * Creates an entry for the attempt in the database, fetching the id
     * of it and storing it in the class. Note that no values need to
     * be entered in the database; the defaults for both columns are fine.
     */
    private function addToQueue()
    {
        $sql = "INSERT INTO login_attempt_queue (ip_address, username)
                VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        try {
            $stmt->execute(array(
                sprintf('%u', ip2long($_SERVER["REMOTE_ADDR"])),
                $this->username
            ));
            $this->attemptID = (int)$this->pdo->lastInsertId();
        }
        catch (PDOException $e) {
            throw new Exception("IP address is already in queue.", 403);
        }
    }

    /**
     * Checks the queue size. Throws an exception if it has been exceeded. Otherwise it does nothing.
     *
     * @throws Exception
     * @return bool
     */
    private function isQueueSizeExceeded()
    {
        $sql = "SELECT
                    COUNT(*) AS overall,
                    COUNT(IF(username = ?, TRUE, NULL)) AS user
                FROM login_attempt_queue
                WHERE last_checked > NOW() - INTERVAL ? MICROSECOND";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(
            $this->username,
            self::ATTEMPT_EXPIRATION_TIMEOUT * 1000
        ));

        $count = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$count) {
            throw new Exception("Failed to query queue size", 500);
        }

        return ($count->overall >= self::MAX_OVERALL || $count->user >= self::MAX_PER_USER);
    }

    /**
     * Checks if the login attempt is ready to be processed, and updates the
     * last_checked timestamp to keep the attempt alive.
     *
     * @return bool
     */
    private function isReady()
    {
        if (!$this->readyCheckStatement) {
            $sql = "SELECT id FROM login_attempt_queue
                    WHERE
                        last_checked > NOW() - INTERVAL ? MICROSECOND AND
                        username = ?
                    ORDER BY id ASC
                    LIMIT 1";
            $this->readyCheckStatement = $this->pdo->prepare($sql);
        }
        $this->readyCheckStatement->execute(array(
            self::ATTEMPT_EXPIRATION_TIMEOUT * 1000,
            $this->username
        ));
        $result = (int)$this->readyCheckStatement->fetchColumn();

        if (!$this->checkUpdateStatement) {
            $sql = "UPDATE login_attempt_queue
                    SET last_checked = CURRENT_TIMESTAMP
                    WHERE id = ? LIMIT 1";
            $this->checkUpdateStatement = $this->pdo->prepare($sql);
        }
        $this->checkUpdateStatement->execute(array($this->attemptID));

        return $result === $this->attemptID;
    }

    /**
     * Checks if the login attempt is valid. Note that this function will cause
     * the delay between attempts when first called. If called multiple times,
     * only the first call will do so.
     *
     * @return bool
     */
    public function isValid()
    {
        if ($this->isLoginValid === null) {
            $sql = "SELECT password
                    FROM users
                    WHERE username = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array($this->username));
            $realHash = $stmt->fetchColumn();

            if ($realHash) {
                $this->isLoginValid = password_verify($this->password, $realHash);
            }
            else {
                $this->isLoginValid = false;
            }

            // Sleep at this point, to enforce a delay between login attempts.
            usleep(self::ATTEMPT_DELAY  * 1000);

            // Remove the login attempt from the queue, as well as any login
            // attempt that has timed out.
            $sql = "DELETE FROM login_attempt_queue
                    WHERE
                        id = ? OR
                        last_checked < NOW() - INTERVAL ? MICROSECOND";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array(
                $this->attemptID,
                self::ATTEMPT_EXPIRATION_TIMEOUT * 1000
            ));
        }

        return $this->isLoginValid;
    }

    /**
     * Calls the callback function when the login attempt is ready, passing along the
     * result of the validation as the first parameter.
     *
     * @param callable|string $callback
     * @param int $checkTimer Delay between checks, in milliseconds.
     */
    public function whenReady($callback, $checkTimer=250)
    {
        while (!$this->isReady()) {
            usleep($checkTimer * 1000);
        }

        if (is_callable($callback)) {
            call_user_func($callback, $this->isValid());
        }
    }
}


