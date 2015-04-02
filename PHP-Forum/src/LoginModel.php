<?php
require_once 'DBDetails.php';
	class LoginModel
	{
		private $correctUsername = "";
		private $correctPassword = "";
		private $sessionUserAgent;
		private $success = false;
		private $db;
		
		
		protected $dbUsername = "root";
		protected $dbPassword = "";
		protected $dbConnstring = 'mysql:host=127.0.0.1;dbname=login';
		protected $dbConnection;
		protected $dbTable = "login";
		
		
		public function __construct($userAgent)
		{
			// Sparar anv�ndarens useragent i den privata variablerna.
			$this->sessionUserAgent = $userAgent;
			$this->db = new DBDetails();
		}
		
		// Kontrollerar loginstatusen. �r anv�ndaren inloggad returnerar metoden true, annars false.
		public function checkLoginStatus()
		{
			if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true && $_SESSION['sessionUserAgent'] === $this->sessionUserAgent)
			{
				return true;
			}
			
			return false;
		}
		public function CheckBothRegInput($registerUsername,$registerPassword){
			if(mb_strlen($registerUsername) < 3 && mb_strlen($registerPassword) < 6){
				// Kasta undantag.
				throw new Exception("Username must have at least 3 characters and a password with at least 6 characters.");
				
			}
			return true;
		}
		public function CheckRegUsernameLength($registerUsername){
			if(mb_strlen($registerUsername) < 3){
				// Kasta undantag.
				throw new Exception("Username must have at least 3 characters.");
				
			}
			return true;
			
		}
		public function CheckReqPasswordLength($registerPassword){
			if(mb_strlen($registerPassword) < 6){
				// Kasta undantag.
				throw new Exception("Password must have at least 6 characters");
				
				
			}
			return true;
			
			
		}
		public function ComparePasswordRepPassword($registerPassword, $repeatPassword){
				if($registerPassword !== $repeatPassword)
				{
					throw new Exception("Passwords do not match!");
				}
				return true;
		}
		
			
	
		protected function connection() {
			if ($this->dbConnection == NULL)
				$this->dbConnection = new \PDO($this->dbConnstring, $this->dbUsername, $this->dbPassword);
			
			$this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
			return $this->dbConnection;
		}
		
	
		public function add($inputuser,$inputpassword) {
			
			try {
				$db = $this -> connection();
				
				$sql = "INSERT INTO $this->dbTable (`username`,`password`) VALUES (?, ?)";
				$params = array($inputuser, $inputpassword);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$this->success = true;
			} catch (\PDOException $e) {
				die('An unknown error have occured.');
			}
		}
		public function ReadSpecifik($inputuser)
		{
			
			$db = $this -> connection();
			$sql = "SELECT `username` FROM `$this->dbTable` WHERE `username` = ?";
			$params = array($inputuser);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			$result = $query -> fetch();
			
			
			if ($result['username'] !== null) {
				
				$this->UsernameExistInDB();
			}else{
				return true;
			}
			
		
		}
		public function UsernameExistInDB(){
		
				throw new Exception("Username is already taken");
		}
		public function ValidateUsername($inputuser){
			
			if(!preg_match('/^[A-Za-z][A-Za-z0-9]{1,31}$/', $inputuser))
			{
				throw new Exception("Username contains invalid characters");
			}
			return true;
		}
		public function UserRegistered(){
			return $this->success;
		}
		public function UserLogin(){
			return $this->loginsuccess;
		}
		
		
		public function sanitizeString($string){
			//$sanitizedString = mysqli_real_escape_string($string);
			if(!preg_match('/^[A-Za-z][A-Za-z0-9]{1,31}$/', $string))
				{
					throw new Exception("Input contains invalid characters!");
				}
			return true;
			
			
		}
		
		public function verifyChangeUserInput($inputUsername, $inputPassword)
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
				{
					
					$recaptcha=$_POST['g-recaptcha-response'];
					if(!empty($recaptcha))
					
				{
					
					include("src/getCurlData.php");
					$google_url="https://www.google.com/recaptcha/api/siteverify";
					$secret='6LdK9AMTAAAAAKTOAvNLeThud6yglIw8K5g62yTx';
					$ip=$_SERVER['REMOTE_ADDR'];
					$url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
					$res=getCurlData($url);
					$res= json_decode($res, true);
					
					if($res['success'] == NULL)
					{
			
						
						$db = $this -> connection();
						$sql = "SELECT * FROM `$this->dbTable` WHERE username= ? AND password= ?";
						$params = array($inputUsername, $inputPassword);
						$query = $db -> prepare($sql);
						$query -> execute($params);
						$rows = $query -> fetchColumn();
						
						if($rows) {
							return TRUE;
						}
						else{
							throw new Exception("Wrong username or password");
						}
					}
					else
					{
						throw new Exception("Wrong captcha kod");
					}
				
				}
				else
				{
				throw new Exception("Wrong Captcha");
				}
				}
				
		}
		
		// Kontrollerar anv�ndarinput gentemot de faktiska anv�ndaruppgifterna.
		public function verifyUserInput($inputUsername, $inputPassword, $isCookieLogin = false)
		{

			if($inputUsername == "" || $inputUsername === NULL)
			{
				// Kasta undantag.
				throw new Exception("Username missing");
			}
			
			if($inputPassword == "" || $inputPassword === NULL)
			{
				// Kasta undantag.
				throw new Exception("Password missing");
			}
			if($this->sanitizeString($inputUsername)){
				
			}
			
			
			$msg='';
				if($_SERVER["REQUEST_METHOD"] == "POST")
				{
					
					$recaptcha=$_POST['g-recaptcha-response'];
					if(!empty($recaptcha))
					
				{
					
					include("src/getCurlData.php");
					$google_url="https://www.google.com/recaptcha/api/siteverify";
					$secret='6LdK9AMTAAAAAKTOAvNLeThud6yglIw8K5g62yTx';
					$ip=$_SERVER['REMOTE_ADDR'];
					$url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
					$res=getCurlData($url);
					$res= json_decode($res, true);
					
					if($res['success'] == NULL)
					{
						
						$db = $this -> connection();
						$sql = "SELECT * FROM `$this->dbTable` WHERE username= ? AND password= ?";
						$params = array($inputUsername, $inputPassword);
						$query = $db -> prepare($sql);
						$query -> execute($params);
						$rows = $query -> fetchColumn();
						
						if($rows) {
							
							$this->db->LogAction($inputUsername, "User logged in");
							
							//Hämtar ut användarens roll ifall användarnamn och lösen matchar
							$db = $this -> connection();
							$sql = "SELECT `role` FROM `$this->dbTable` WHERE `username` = ?";
							$params = array($inputUsername);
							$query = $db -> prepare($sql);
							$query -> execute($params);
							$result = $query -> fetch();
							//echo $result;
							// Inloggningsstatus, roll och anv�ndarnamn sparas i sessionen.
							$_SESSION['role'] = $result['role'];
							$_SESSION['loggedIn'] = true;
							$_SESSION['loggedInUser'] = $inputUsername;
							
							// Sparar useragent i sessionen.
							$_SESSION['sessionUserAgent'] = $this->sessionUserAgent;
							
							return TRUE;
						}
						else{
														// �r det en inloggning med cookies...
							if($isCookieLogin)
							{
								// Kasta cookie-felmeddelande.
								$this->cookieException();
							}
							
							// Kasta undantag.
							$this->db->LogAction($inputUsername, "User failed to log in");
							throw new Exception("Wrong username or password");
						}

						}

					}
					else
					{
						throw new Exception("Wrong captcha kod");
					}
				
				}
				else
				{
				throw new Exception("Wrong Captcha");
				}
				
				
				
		}
		
		public function cookieException()
		{
			// Kasta cookie-felmeddelande.
			throw new Exception("Invalid cookie information");
		}
		
		// H�mtar anv�ndarnamnet fr�n sessionen.
		public function getLoggedInUser()
		{
			if(isset($_SESSION['loggedInUser']))
			{
				return $_SESSION['loggedInUser'];
			}
		}
		
		public function getLoggedInUserRole()
		{
			if(isset($_SESSION['role']))
			{
				return $_SESSION['role'];
			}
		}
		
		// Logout-metod som avs�tter och f�rst�r sessionen.
		public function logOut()
		{
			$this->db->LogAction($this->getLoggedInUser(), "User logged out");
			session_unset();
			session_destroy();
		}
		
		// Skapar en fil p� servern som inneh�ller det medskickade objektets v�rden.
		public function createReferenceFile($referenceValue, $fileName)
		{
			// Skapar och �ppnar en textfil.
			$referenceFile = fopen($fileName . ".txt", "w") or die("Unable to open file!");
			
			fwrite($referenceFile, $referenceValue);
			
			// St�nger textfilen.
			fclose($referenceFile);
		}
		
		// Kontrollerar textfilen gentemot kakornas tid.
		public function validateExpirationTime()
		{
			// Variabel som ska inneh�lla tiden fr�n filen.
			$correctTime = "";
			
			// �ppnar filen, l�ser igenom den och sparar v�rdet i $correctTime, f�r att sedan st�nga filen.
			$file = fopen('cookieExpirationTime.txt','r');
			while ($line = fgets($file))
			{
			  $correctTime = $line;
			}
			fclose($file);
			
			// Om tiden fr�n filen �r st�rre �n just precis nu...
			if(intval($correctTime) > time())
			{
				// Returnera true, kakan �r fortfarande giltig.
				return true;
			}
			else
			{
				// Annars kalla p� felmeddelandet, kakans levnadstid �r �ver.
				$this->cookieException();
			}
		}
	}
?>