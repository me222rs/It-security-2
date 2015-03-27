<?php
	
	require_once("ForumView.php");
	require_once("DBDetails.php");
	require_once 'LoginModel.php';
	class EditTopicController{
		
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
			if($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressEditTopic()){
				$topics = $this->db->fetchTopic($this->ShowForumView->getTopicId());
				$this->ShowForumView->showEditTopicForm($topics);
				echo "FFFFFFFFFFFFFFFFF";
				if($this->ShowForumView->didUserPressPostEditButtonTopic()){
					
					$EditTopicName = $this->ShowForumView->getFormTopicEditName();
					$EditTopicText = $this->ShowForumView->getFormTopicEditText();
					$this->db->EditTopic($EditTopicName,$EditTopicText,$this->ShowForumView->getTopicId(), $this->model->getLoggedInUser());
					$newURL = "?topics";
					header('Location: '.$newURL);
					
					//$topics = $this->db->fetchAllTopics();
					//$this->ShowForumView->ShowAllEventsWithBandGrades($topics);
					
				}
				//När topic har tagits bort så visas alla topics
				//$topics = $this->db->fetchAllTopics();
				//$this->ShowForumView->ShowAllEventsWithBandGrades($topics);

				
				
			}
				//$topics = $this->db->fetchAllTopics();
				//$this->ShowForumView->ShowAllEventsWithBandGrades($topics);
		}

	}