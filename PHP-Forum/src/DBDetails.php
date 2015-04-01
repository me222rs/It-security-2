<?php

require_once("TopicList.php");
require_once("CommentList.php");


	class DBDetails{
		//Databasuppgifter för databasen.
		protected $dbUsername = "root";
		protected $dbPassword = "";
		protected $dbConnstring = 'mysql:host=127.0.0.1;dbname=login';
		protected $dbConnection;
		protected $dbTable = "";
		//privata statiska variabler som används för att undvika strängberoenden i metoderna.
		private static $tblTopics = "topics";
		private static $topicid = "topicID";
		private static $topicName = "topicName";
		private static $topicText = "topicText";
		private static $topicOwnerID = "topicOwnerID";
		
		private static $commentID = "commentID";
		private static $topicId = "topicId";
		private static $comment = "comment";
		private static $commentPoster = "commentPoster";
		
		
		private static $username = "username";
		private static $password = "password";
		
		
		private static $User = "User";
		private static $Action = "Action";
		private static $Timestamp = "Timestamp";
		
		//private static $topicId = "topicID";
		private static $tblLogin = "login";
		private static $tblComments = "topiccomments";
		private static $tblLogs = "logs";
		
		
		private static $id = "id";
		private static $tblUser = "user";
		
		
		private static $colusername = "username";
		private static $colpassword = "password";
		private static $ID = "ID";
		//returnerar anslutningssträngen.
		
		
		public function sanitizeString($string){
			
			if(!preg_match('/^[a-zA-Z0-9 .,!?-]+$/', $string))
				{
					throw new Exception("Input contains invalid characters!");
				}
			return true;
		}
		
		public function CheckLenghtTopicName($topicName){
			
			if(mb_strlen($topicName) < 1 || mb_strlen($topicName) > 39){
				// Kasta undantag.
				
				throw new Exception("Topic name must be at least 1 character and less than 40 characters");
				
			}
			return true;
			
		}
		public function CheckLenghtTopicTextAndComment($topicTextAndComment){
			if(mb_strlen($topicTextAndComment) < 1 || mb_strlen($topicTextAndComment) > 300){
				// Kasta undantag.
				throw new Exception("Text must be less than 300 characters and contain at least 1 character");
				
			}
			return true;
			
		}
		
		
		protected function connection() 
		{
			if ($this->dbConnection == NULL)
					$this->dbConnection = new \PDO($this->dbConnstring, $this->dbUsername, $this->dbPassword);
			
			$this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				
			return $this->dbConnection;
		}
		
		
		public function ChangePassword($user, $oldPassword, $newPassword)
		{
			try{
				
			$db = $this -> connection();
			$this->dbTable = self::$tblLogin;
			$sql = "UPDATE $this->dbTable SET ". self::$password ."=? WHERE ". self::$username ."=?";
			$params = array($newPassword, $user);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			
			$this->LogAction($user, "Changed password");
					
			} catch (\PDOException $e) {
					die('An unknown error have occured.');
			}
        
		}
		
		
		public function checkIfIdIsManipulated($pickedId, $loggedinUser)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblComments;
				$sql = "SELECT ". self::$commentID .",". self::$commentPoster ." FROM `".$this->dbTable."` WHERE ". self::$commentID ." = ? AND ". self::$commentPoster ." = ? ";
				$params = array($pickedId, $loggedinUser);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$commentID] == null && $result[self::$commentPoster] == null ) {
					throw new Exception("You don't have the rights to do this");
				}else{
					return true;
				}
			
		}

		public function checkIfIdIsManipulatedTopic($pickedId, $loggedinUser)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblTopics;
				$sql = "SELECT ". self::$topicid .",". self::$topicOwnerID ." FROM `".$this->dbTable."` WHERE ". self::$topicid ." = ? AND ". self::$topicOwnerID ." = ? ";
				$params = array($pickedId, $loggedinUser);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$topicid] == null && $result[self::$topicOwnerID] == null ) {
					throw new Exception("You don't have the rights to do this");
				}else{
					return true;
				}
			
		}
		
		
		public function LogAction($user, $action){
			try{
				$db = $this -> connection();
				$this->dbTable = self::$tblLogs;
				$sql = "INSERT INTO $this->dbTable (". self::$User .",". self::$Action  .") VALUES (?, ?)";
				$params = array($user, $action);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				
			} catch (\PDOException $e) {
				die('An unknown error have occured.');
			}
		}
		
		
		public function EditComment($editCommentText, $commentID, $user)
		{
			try{
				
			$db = $this -> connection();
			$this->dbTable = self::$tblComments;
			$sql = "UPDATE $this->dbTable SET ". self::$comment ."=? WHERE ". self::$commentID ."=?";
			$params = array($editCommentText,$commentID);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			
			$this->LogAction($user, "Edited comment with id " . $commentID . ". Changed to " . "text:" . $editCommentText);
					
			} catch (\PDOException $e) {
					die('An unknown error have occured.');
			}
        
		}
		
		
		public function createComment($commentText, $topicID, $user){
			
			try{
				$db = $this -> connection();
				$this->dbTable = self::$tblComments;
				$sql = "INSERT INTO $this->dbTable (". self::$comment .",". self::$commentPoster .", ". self::$topicId .") VALUES (?, ?, ?)";
				$params = array($commentText, $user, $topicID);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$this->LogAction($user, "Created comment in topic with id" . $topicID);
				
			} catch (\PDOException $e) {
				//$this->LogAction($a_user, "Failed to create topic with name: " . $a_TopicName . " and text: " . $a_topicText);
				die('An unknown error have occured.');
			}
		}
		
		
		
		public function createNewTopic($a_TopicName, $a_topicText, $a_user){
			
			try{
				$db = $this -> connection();
				$this->dbTable = self::$tblTopics;
				$sql = "INSERT INTO $this->dbTable (". self::$topicName .",". self::$topicText  .", ". self::$topicOwnerID  .") VALUES (?, ?, ?)";
				$params = array($a_TopicName, $a_topicText, $a_user);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$this->LogAction($a_user, "Created topic with name: " . $a_TopicName . " and text: " . $a_topicText);
				
			} catch (\PDOException $e) {
				$this->LogAction($a_user, "Failed to create topic with name: " . $a_TopicName . " and text: " . $a_topicText);
				die('An unknown error have occured.');
			}
		}
		
		public function DeleteTopic($topicID, $user){
			try{
			$db = $this -> connection();
			$this->dbTable = self::$tblTopics;
			$sql = "DELETE FROM $this->dbTable WHERE ". self::$topicId ." = ?";
			$params = array($topicID);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			
			$this->LogAction($user, "Deleted topic with id " . $topicID);
			
			}catch (\PDOException $e) {
				
				die('An unknown error have occured.');
			}
		}

		public function DeleteComment($commentID, $user){
			try{
			$db = $this -> connection();
			$this->dbTable = self::$tblComments;
			$sql = "DELETE FROM $this->dbTable WHERE ". self::$commentID ." = ?";
			$params = array($commentID);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			
			$this->LogAction($user, "Deleted comment with id " . $commentID);
			
			}catch (\PDOException $e) {
				$this->LogAction($user, "Failed to delete comment with id " . $commentID);
				die('An unknown error have occured.');
			}
		}
		
		public function fetchComment($commentId)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblComments;
				$sql = "SELECT * FROM `$this->dbTable` WHERE commentID = ?";
				$params = array($commentId);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetchall();
				
				
				$Comments = new CommentList();
				foreach ($result as $commentdb) {
					$Comment = new Comment($commentdb[self::$commentID], $commentdb[self::$topicId], $commentdb[self::$comment], $commentdb[self::$commentPoster]);
					$Comments->add($Comment);
				}
				return $Comments;
		}
		
		
		public function fetchAllComments($topicId){
		
				$db = $this -> connection();
				$this->dbTable = self::$tblComments;
				$sql = "SELECT * FROM `$this->dbTable` WHERE topicId = ?";
				$params = array($topicId);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetchall();

				
				$Comments = new CommentList();
				foreach ($result as $commentdb) {
					$Comment = new Comment($commentdb[self::$commentID], $commentdb[self::$topicId], $commentdb[self::$comment], $commentdb[self::$commentPoster]);
					$Comments->add($Comment);
				}
				
				
				return $Comments;
		}
		
		public function fetchAllTopics()
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblTopics;
				$sql = "SELECT * FROM `$this->dbTable`";
				
				$query = $db -> prepare($sql);
				$query -> execute();
				$result = $query -> fetchall();
				
				
				$Topics = new TopicList();
				foreach ($result as $topicdb) {
					$Topic = new Topic($topicdb[self::$topicName], $topicdb[self::$topicid], $topicdb[self::$topicText], $topicdb[self::$topicOwnerID]);
					$Topics->add($Topic);
				}
				
				return $Topics;
		}
		
		public function fetchTopic($topicId)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblTopics;
				$sql = "SELECT * FROM `$this->dbTable` WHERE topicID = ?";
				$params = array($topicId);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetchall();
				
				
				$Topics = new TopicList();
				foreach ($result as $topicdb) {
					$Topic = new Topic($topicdb[self::$topicName], $topicdb[self::$topicid], $topicdb[self::$topicText], $topicdb[self::$topicOwnerID]);
					$Topics->add($Topic);
				}
				return $Topics;
		}
		
		
		public function fetchUserById($userId)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblLogin;
				$sql = "SELECT username FROM `$this->dbTable` WHERE id = ?";
				$params = array($userId);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
				
				
				return $result;
			
		}
		
		
		//Kontrollerar om användarnamnet är upptaget, returnerar true om det inte är upptaget. Annars kastas undantag.
		public function ReadSpecifik($inputuser)
		{
			
			$db = $this -> connection();
			$this->dbTable = self::$tblUser;
			$sql = "SELECT ". self::$username ." FROM `$this->dbTable` WHERE ". self::$username ." = ?";
			$params = array($inputuser);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			$result = $query -> fetch();
			
			
			if ($result[self::$colusername] !== null) {
				
				throw new Exception("Användarnamnet är redan upptaget");
			}else{
				return true;
			}
			
		
		}	
		//Hämtar och returnerar användarnamnet från databasen.
		public function getDBUserInput($inputUser)
		{
			$db = $this -> connection();
			$this->dbTable = self::$tblUser;
			$sql = "SELECT ". self::$username ." FROM `$this->dbTable` WHERE ". self::$username ." = ?";
			$params = array($inputUser);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			$result = $query -> fetch();
			
			
			if ($result) {
				
				return $result[self::$colusername];
				
			}
		}
		//Hämtar och returnerar lösenordet från databasen.
		// public function getDBPassInput($inputPassword)
		// {
			// $db = $this -> connection();
			// $this->dbTable = self::$tblUser;
			// $sql = "SELECT ". self::$password ." FROM `$this->dbTable` WHERE ". self::$password ." = ?";
			// $params = array($inputPassword);
			// $query = $db -> prepare($sql);
			// $query -> execute($params);
			// $result = $query -> fetch();
// 			
// 			
			// if ($result) {
// 				
				// return $result[self::$colpassword];
// 				
			// }
		// }
		
		
		
		//Kontrollerar om id värde har manipulerats till något annat. Om inte så returneras true annars kastas undantag.
		public function checkIfIdManipulated($pickedid, $loggedinUser)
		{
				$db = $this -> connection();
				$this->dbTable = self::$tblSummaryGrade;
				$sql = "SELECT ". self::$id .",". self::$username ." FROM `".$this->dbTable."` WHERE ". self::$id ." = ? AND ". self::$username ." = ? ";
				$params = array($pickedid,$loggedinUser);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$result = $query -> fetch();
								
				
				if ($result[self::$colId] == null && $result[self::$colusername] == null ) {
					throw new Exception("Id till det betyget har inte rätt användarnamn");
				}else{
					return true;
				}
		}
		
		
		//Lägger till användaren med användarnamn och lösenord och returnerar true för att sätta en variabel i LoginModel klassen.
		public function addUser($inputuser,$inputpassword) {
			try {
				$db = $this -> connection();
				$this->dbTable = self::$tblUser;
				$sql = "INSERT INTO $this->dbTable (". self::$username .",". self::$password  .") VALUES (?, ?)";
				$params = array($inputuser, $inputpassword);
				$query = $db -> prepare($sql);
				$query -> execute($params);
				$this->LogAction("Added user: " . $inputuser);
				return true;
			} catch (\PDOException $e) {
				die('An unknown error have occured.');
			}
		}
		
		
		
		
		
		public function EditTopic($EditTopicName,$EditTopicText, $topicID, $user)
		{
			try{
				
			$db = $this -> connection();
			$this->dbTable = self::$tblTopics;
			$sql = "UPDATE $this->dbTable SET ". self::$topicName ."=?,". self::$topicText ."=? WHERE ". self::$topicId ."=?";
			$params = array($EditTopicName,$EditTopicText,$topicID);
			$query = $db -> prepare($sql);
			$query -> execute($params);
			
			$this->LogAction($user, "Edited topic with id " . $topicID . ". Changed to " . " name: " . $EditTopicName . " & " . "text:" . $EditTopicText);
					
			} catch (\PDOException $e) {
					die('An unknown error have occured.');
			}
        
		}
		
		
	}