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
			echo "tryckt på delete comment";
			return isset($_GET['deleteComment']);
		}
		
		public function didUserPressEditTopic(){
			return isset($_GET['edit']);
		}
		
		public function didUserPressEditComment(){
			//echo "tryckt på edit comment";
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
		
		// public function didUserPressPostEditTopic()
		// {
			// return isset($_GET['postEditTopic']);
		// }
		
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
				echo "tryckt på send";
				return TRUE;
			}
			return FALSE;
		}
		public function didUserPressPostComment(){
			if(isset($_POST['sendComment'])){
				echo "tryckt på send comment";
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
				echo "tryckt på edit2";
				return TRUE;
			}
			return FALSE;
		}
		
		public function didUserPressPostEditButtonComment(){
			if(isset($_POST['sendEditComment'])){
				echo "tryckt på edit comment knappen";
				return TRUE;
			}
			return FALSE;
		}
		
		
		
		
		
		
		public function showTopics(TopicList $topicList, CommentList $commentList){
					//$contentString ="<form method=post ><h3>Visar Alla Trådar</h3>";
				//var_dump($commentList);
					foreach($topicList->toArray() as $topic)
					{
						//$userId = $this->getUserById($topic->getOwner());
						//".array_values($userId)[0]."
						$contentString ="<form method=post ><h3>".$topic->getName()."</h3>";
						$contentString .= "<a href='?delete&topicId=".$this->getTopicId()."'>Delete</a> ";
						$contentString .= "<a href='?edit&topicId=".$this->getTopicId()."'>Edit</a>"; 	
						$contentString .= "<fieldset class='fieldshowall'>";
						$contentString.= "".$topic->getText()."<br>";
						$contentString.= "Written by: ".$topic->getOwner()."";
						$contentString .= "</fieldset>";
						
						$contentString .= "";
						
						
						
					}
					$contentString .= "Comments here!";
					
					foreach($commentList->toArray() as $comment){
						
						$contentString .= "<fieldset class='fieldshowall'>";
						$contentString .= "<a href='?deleteComment&commentId=".$comment->getCommentID()."'>Delete</a> ";
						$contentString .= "<a href='?editComment&commentId=".$comment->getCommentID()."'>Edit</a><br>";
						$contentString.= "".$comment->getComment()."<br>";
						$contentString.= "Written by: ".$comment->getCommentPoster()."";
						$contentString .= "</fieldset>";
						
					}
					$contentString .= "	<textarea type='text' name='CommentText'></textarea><br>
						
										<input type='submit' name='sendComment'  value='Send'>";	 
					$contentString .= "</form>";
					
					$HTMLbody = "<div class='divshowall'>
					<h1>Forum</h1>
					<p><a href='?login'>Tillbaka</a></p>
					$contentString</div>";
					
					$this->echoHTML($HTMLbody);
		}
		
		public function ShowAllEventsWithBandGrades(TopicList $topicList)
			{
				echo "test1";
					$contentString ="<form method=post ><h3>Visar Alla Trådar</h3>";
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
					<p><a href='?login'>Tillbaka</a></p>
					$contentString</div>";
					$this->echoHTML($HTMLbody);
			}
		
		
		//Visar en hel forumtråd
		public function ShowForumPost($topicID){
			
				$HTMLbody = "
					
				<form method=post >
						<fieldset>
							testar $topicID
						</fieldset>
					</form>
				
				";
			
			$this->echoHTML($HTMLbody);
		}
		
		
		//Visa skapa nytt ämne formulär
		public function showNewTopicForm(){
				$HTMLbody = "
				<form method=post >
						<fieldset>
							<a href='?login'>Tillbaka</a><br>
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

						$contentString .= "<fieldset class='fieldshowall'><span class='spangradient'  style='white-space: nowrap'>hejsan:</span><br>
						
						
							<textarea type='text' name='commentEditText'>".$comment->getComment()."</textarea><br>
						
							
						
						
						";
						
						$contentString.= "Written by: ".$comment->getCommentPoster()." <br>Skicka: <input type='submit' name='sendEditComment'  value='Send'>";
						$contentString .= "</fieldset>";
						
						$contentString .= "Comments here!";
						
						
					}
							 
					$contentString .= "</form>";
					
					$HTMLbody = "<div class='divshowall'>
					<h1>Forum</h1>
					$this->message
					<p><a href='?login'>Tillbaka</a></p>
					$contentString</div>";
					$this->echoHTML($HTMLbody);
			
			
		}
		
		
		public function showEditTopicForm(TopicList $topicList){
					foreach($topicList->toArray() as $topic)
					{
						//$userId = $this->getUserById($topic->getOwner());
						//".array_values($userId)[0]."
						$contentString ="<form method=post ><h3>".$topic->getName()."</h3>";

						$contentString .= "<fieldset class='fieldshowall'><span class='spangradient'  style='white-space: nowrap'>Trådar:</span><br>
						
						<input type='text' name='topicEditName' value='".$topic->getName()."'><br>
							<textarea type='text' name='topicEditText'>".$topic->getText()."</textarea><br>
						
							
						
						
						";
						
						$contentString.= "Written by: ".$topic->getOwner()." <br>Skicka: <input type='submit' name='sendEditTopic'  value='Send'>";
						$contentString .= "</fieldset>";
						
						$contentString .= "Comments here!";
						
						
					}
							 
					$contentString .= "</form>";
					
					$HTMLbody = "<div class='divshowall'>
					<h1>Forum</h1>
					<p><a href='?login'>Tillbaka</a></p>
					$contentString</div>";
					$this->echoHTML($HTMLbody);
			
			
		}
		
		//Redigera sin egen post
		public function EditTopicForm(){
				$HTMLbody = "
				<form method=post >
						<fieldset>
							<legend>Edit topic</legend>
							Topic name: <br>
							<input type='text' name='topicName'><br>
							<textarea type='text' name='editTopicText'></textarea><br>
						
							Skicka: <input type='submit' name='sendEditTopic'  value='Send'>
						</fieldset>
					</form>
				
				";
			
			$this->echoHTML($HTMLbody);
		}
		
		//Svara i en forumtråd
		public function PostTopicComment(){
			
				$HTMLbody = "
				
				";
			
			$this->echoHTML($HTMLbody);
		}
		
		//Svara på en post i en forumtråd
		public function PostTopicReply(){
			
				$HTMLbody = "
				
				";
			
			$this->echoHTML($HTMLbody);
		}
		
		// Visar eventuella meddelanden.
		public function showMessage($message)
		{
			$this->message = "<p>" . $message . "</p>";
		}
	}
?>