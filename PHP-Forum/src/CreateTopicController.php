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
			$this->doControll();

		}
		
		
		public function doControll()
		{
			if($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressCreateNewTopic()){

				
				try{
					if($this->ShowForumView->didUserPressPostTopic() && $this->db->sanitizeString($this->ShowForumView->getFormTopicName()) && 
						$this->db->sanitizeString($this->ShowForumView->getFormTopicText()) && $this->db->CheckLenghtTopicName($this->ShowForumView->getFormTopicName()) &&
						$this->db->CheckLenghtTopicTextAndComment($this->ShowForumView->getFormTopicText())){
						
						$topicName = $this->ShowForumView->getFormTopicName();
						$topicText = $this->ShowForumView->getFormTopicText();

						
						$this->db->createNewTopic($topicName, $topicText, $this->model->getLoggedInUser());
						$this->ShowForumView->successfulEdit();
					}
				}catch(Exception $e){
					$this->ShowForumView->showMessage($e->getMessage());
				}
				
				
				$this->doHTMLBody();
				
				
			}

		}
		public function doHTMLBody(){
			if($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressCreateNewTopic()){

				$this->ShowForumView->showNewTopicForm();
			}
		}

	}