<?php
require_once 'common/HTMLView.php';
	class ForumView extends HTMLView
	{
		private $model;
		public function __construct()
		{
			
		}
		
		public function didUserPressCreateNewTopic()
		{
			return isset($_GET['create']);
		}
		
		// public function didUserPressTopic()
		// {
			// return isset($_GET['topics']);
		// }
		public function didUserPressShowTopics(){
			echo "tryckt på show topics";
			return isset($_GET['topics']);
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
		
		public function ShowAllEventsWithBandGrades(TopicList $topicList)
			{
				echo "test1";
					$contentString ="<form method=post ><h3>Visar Alla Trådar</h3>";
	
					foreach($topicList->toArray() as $topic)
					{
							 	
						$contentString .= "<fieldset class='fieldshowall'><span class='spangradient'  style='white-space: nowrap'>Trådar:</span>";
						$contentString.= "<p class='pgradient'>".$topic->getName()."</p>";
						$contentString .= "</fieldset>";
					}
							 
					$contentString .= "</form>";
					
					$HTMLbody = "<div class='divshowall'>
					<h1>Visar alla events med band och betyg</h1>
					<p><a href='?login'>Tillbaka</a></p>
					$contentString</div>";
					$this->echoHTML($HTMLbody);
			}
		
	}
?>