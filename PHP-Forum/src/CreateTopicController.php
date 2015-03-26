<?php
	
	require_once("ForumView.php");
	require_once("DBDetails.php");
	require_once 'LoginModel.php';
	class CreateTopicController{
		
		private $ShowForumView;
		private $db;
		private $model;
		
		
		public function __construct(){
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
			$this->model = new LoginModel($userAgent);
			$this->ShowForumView = new ForumView($this->model);
			$this->db = new DBDetails();

		}
		
		// public function doCreateNewTopic(){
			// $this->ShowForumView->didUserPressPostTopic();
// 			
		// }
		
		
		//anropar vilken vy som ska visas.
		public function doHTMLBody()
		{
			if($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressCreateNewTopic()){

				$this->ShowForumView->showNewTopicForm();
				
				if($this->ShowForumView->didUserPressPostTopic()){
					$topicName = $this->ShowForumView->getFormTopicName();
					$topicText = $this->ShowForumView->getFormTopicText();
					
					$this->db->createNewTopic($topicName, $topicText, $this->model->getLoggedInUser());
				}
				//När topic har tagits bort så visas alla topics
				//$topics = $this->db->fetchAllTopics();
				//$this->ShowForumView->ShowAllEventsWithBandGrades($topics);

				
				
			}

		}

	}