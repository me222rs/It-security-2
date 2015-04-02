<?php
require_once 'common/HTMLView.php';
require_once 'DBDetails.php';
	class ForumView extends HTMLView
	{
		private $model;
		private $db;
		private $message = "";
		
		public function __construct(LoginModel $model)
		{
			$this->model = $model;
			$this->db = new DBDetails();
		}
		
		public function didUserPressDeleteTopic(){
			return isset($_GET['delete']);
		}

		public function didUserPressDeleteComment(){
			return isset($_GET['deleteComment']);
		}
		
		public function didUserPressEditTopic(){
			return isset($_GET['edit']);
		}
		
		public function didUserPressEditComment(){
			return isset($_GET['editComment']);
		}
		
		public function didUserPressCreateNewTopic()
		{
			return isset($_GET['create']);
		}
		
		public function didUserPressPostNewTopic()
		{
			return isset($_GET['postNewTopic']);
		}
		
		public function didUserPressChangePassword()
		{
			return isset($_GET['changepw']);
		}
		public function didUserPressPostNewPassword(){
			if(isset($_POST['sendNewPassword'])){
				return TRUE;
			}
			return FALSE;
		}
		
		//Change password
		public function getCurrentPassword(){
			if(isset($_POST['currentPassword']))
			{
				return md5("45gt4ad". $_POST['currentPassword'] . "55uio11");
			}
			return false;
		}
		
		public function getNewPassword(){
			if(isset($_POST['newPassword']) && $this->model->CheckReqPasswordLength($_POST['newPassword']))
			{
				return md5("45gt4ad". $_POST['newPassword'] . "55uio11");
			}
			return false;
		}
		
		public function getNewRepeatPassword(){
			if(isset($_POST['repeatNewPassword']) && $this->model->CheckReqPasswordLength($_POST['repeatNewPassword']))
			{
				return md5("45gt4ad". $_POST['repeatNewPassword'] . "55uio11");
			}
			return false;
		}
		
		
		public function didUserPressTopic()
		{
			return isset($_GET['topics']);
		}
		
		public function didUserPressThread(){
			if(isset($_GET['topicId'])){
				return true;
			}
			return false;
		}
		
		public function getTopicId(){
			return $_GET['topicId'];
		}

		public function getCommentID(){
			return $_GET['commentId'];
		}
		
		
		public function getFormTopicName(){
			if(isset($_POST['topicName']))
			{
				return $_POST['topicName'];
			}
			return false;
		}
		
		public function getFormTopicEditName(){
			if(isset($_POST['topicEditName']))
			{
				return $_POST['topicEditName'];
			}
			return false;
		}
		
		public function getFormTopicText(){
			if(isset($_POST['topicText']))
			{
				return $_POST['topicText'];
			}
			return false;
		}
		
		public function getFormTopicEditText(){
			if(isset($_POST['topicEditText']))
			{
				return $_POST['topicEditText'];
			}
			return false;
		}
		public function getUserById($userId){
			return $this->db->fetchUserById($userId);
			
		}
		public function didUserPressPostTopic(){
			if(isset($_POST['sendNewTopic'])){
				return TRUE;
			}
			return FALSE;
		}
		public function didUserPressPostComment(){
			if(isset($_POST['sendComment'])){
				return TRUE;
			}
			return FALSE;
		}
		
		public function getFormCommentText(){
			if(isset($_POST['CommentText']))
			{
				return $_POST['CommentText'];
			}
			return false;
		}
		
		public function getFormCommentEditText(){
			if(isset($_POST['commentEditText']))
			{
				return $_POST['commentEditText'];
			}
			return false;
		}
		
		
		public function didUserPressPostEditButtonTopic(){
			if(isset($_POST['sendEditTopic'])){
				return TRUE;
			}
			return FALSE;
		}
		
		public function didUserPressPostEditButtonComment(){
			if(isset($_POST['sendEditComment'])){
				return TRUE;
			}
			return FALSE;
		}
		
		public function successfulEdit()
		{
			$this->showMessage("Success");
		}
		
		public function showTopics(TopicList $topicList, CommentList $commentList){
				
					foreach($topicList->toArray() as $topic)
					{
						
						$contentString ="<form method=post ><h3>".$topic->getName()."</h3>";
						$contentString .= "<a href='?delete&topicId=".$this->getTopicId()."'>Delete</a> ";
						$contentString .= "<a href='?edit&topicId=".$this->getTopicId()."'>Edit</a>"; 	
						$contentString .= "<fieldset class='fieldshowall'>";
						$contentString.= "".$topic->getText()."<br>";
						$contentString.= "Written by: ".$topic->getOwner()."";
						$contentString .= "</fieldset>";
						
					}
					$contentString .= "<h3 class='comments'>Comments here:</h3>";
					
					foreach($commentList->toArray() as $comment){
						
						$contentString .= "<fieldset class='comments'>";
						$contentString .= "<a href='?deleteComment&commentId=".$comment->getCommentID()."'>Delete</a> ";
						$contentString .= "<a href='?editComment&commentId=".$comment->getCommentID()."'>Edit</a><br>";
						$contentString.= "".$comment->getComment()."<br>";
						$contentString.= "Written by: ".$comment->getCommentPoster()."";
						$contentString .= "</fieldset>";
						
					}
					$contentString .= "<p>$this->message</p>";
					$contentString .= "	<textarea class='commentstext' type='text' name='CommentText'></textarea><br>
										<input type='submit' class='comments' name='sendComment'  value='Send'>";	 
					$contentString .= "</form>";
					
					$HTMLbody = "<div class='divshowall'>
					<h1>Forum</h1>
					<p><a href='?login'>Back</a></p>
					$contentString</div>";
					
					$this->echoHTML($HTMLbody);
		}
		
		public function ShowAllTopics(TopicList $topicList)
			{
				
					$contentString ="<form method=post ><h3>Showing all topics</h3>";
					$contentString .= $this->message;
					foreach($topicList->toArray() as $topic)
					{	 	
						$contentString .= "<fieldset class='fieldshowall'>";
						$contentString.= "<a href='?topics&topicId=".$topic->getID()."'>".$topic->getName()."</a>";
						$contentString .= "</fieldset>";
					}	 
					$contentString .= "</form>";
					
					$HTMLbody = "<div class='divshowall'>
					<h1>Forum</h1>
					<p><a href='?login'>Back</a></p>
					$contentString</div>";
					$this->echoHTML($HTMLbody);
			}
		
			public function ChangePasswordForm(){
				$HTMLbody = "
				<form method=post >
						<fieldset class='comments'>
							$this->message
							<a href='?login'>Back</a><br>
							<legend>Change Password</legend>
		
							Current password: <br><input type='password' name='currentPassword'><br>
							New password: <br><input type='password' name='newPassword'><br>
							Repeat new password: <br><input type='password' name='repeatNewPassword'><br>
							<div class='g-recaptcha' data-sitekey='6LdK9AMTAAAAABnYjmV2ZlSrdicAtpcqsxF7mX_M'></div><br>
							<input type='submit' name='sendNewPassword'  value='Change'>
						</fieldset>
					</form>
				
				";
			
			$this->echoHTML($HTMLbody);
		}
		
		
		
		//Visa skapa nytt ämne formulär
		public function showNewTopicForm(){
				$HTMLbody = "
				<form method=post >
						<fieldset class='comments'>
							$this->message
							<a href='?login'>Back</a><br>
							<legend>Create a new topic</legend>
							Topic name: <br>
							<input type='text' name='topicName'><br>
							<textarea type='text' name='topicText'></textarea><br>
							Skicka: <input type='submit' name='sendNewTopic'  value='Send'>
						</fieldset>
					</form>
				
				";
			
			$this->echoHTML($HTMLbody);
		}
		
		
		public function showEditCommentForm(CommentList $commentList){

					foreach($commentList->toArray() as $comment)
					{
						
						
						$contentString = $this->message;
						$contentString ="<form method=post ><h3>Edit comment</h3>";

						$contentString .= "<fieldset class='fieldshowall'><br>
						<textarea type='text' name='commentEditText'>".$comment->getComment()."</textarea><br>";
						$contentString.= "Written by: ".$comment->getCommentPoster()." <br>Skicka: <input type='submit' name='sendEditComment'  value='Send'>";
						$contentString .= "</fieldset>";
						
					}
							 
					$contentString .= "</form>";
					$HTMLbody = "<div class='divshowall'>
					<h1>Forum</h1>
					$this->message
					<p><a href='?login'>Back</a></p>
					$contentString</div>";
					$this->echoHTML($HTMLbody);
			
			
		}
		
		
		public function showEditTopicForm(TopicList $topicList){
					foreach($topicList->toArray() as $topic)
					{
						
						$contentString ="<form method=post ><h3>".$topic->getName()."</h3>";
						$contentString .= $this->message;
						$contentString .= "<fieldset class='fieldshowall'><span class='spangradient'  style='white-space: nowrap'>Topics:</span><br>
						<input type='text' name='topicEditName' value='".$topic->getName()."'><br>
						<textarea type='text' name='topicEditText'>".$topic->getText()."</textarea><br>";
						$contentString.= "Written by: ".$topic->getOwner()." <br>Skicka: <input type='submit' name='sendEditTopic'  value='Send'>";
						$contentString .= "</fieldset>";
						$contentString .= "Comments here:";
					}
							 
					$contentString .= "</form>";
					
					$HTMLbody = "<div class='divshowall'>
					<h1>Forum</h1>
					<p><a href='?login'>Back</a></p>
					$contentString</div>";
					$this->echoHTML($HTMLbody);
			
			
		}
		// Visar login-meddelande f�r "H�ll mig inloggad"-funktionen.
		public function successfulPasswordChange()
		{
			$this->showMessage("Your password have been changed!");
		}
		public function successfulDeletedTopic()
		{
			$this->showMessage("Topic have been deleted!");
		}
		public function successfulDeletedComment()
		{
			$this->showMessage("Comment have been deleted!");
		}

		// Visar eventuella meddelanden.
		public function showMessage($message)
		{
			
			$this->message = "<p class='ptag'>" . $message . "</p>";
		}
	}
?>