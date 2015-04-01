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
			$this->doControll();


		}
		
		
		//anropar vilken vy som ska visas.
		public function doControll()
		{
			try
			{
				if($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressEditTopic() && $this->model->getLoggedInUserRole() == 1){

					
					
					if($this->ShowForumView->didUserPressPostEditButtonTopic() && $this->db->sanitizeString($this->ShowForumView->getFormTopicEditName()) &&
						$this->db->sanitizeString($this->ShowForumView->getFormTopicEditText()) && 
						$this->db->CheckLenghtTopicName($this->ShowForumView->getFormTopicEditName()) &&
						$this->db->CheckLenghtTopicTextAndComment($this->ShowForumView->getFormTopicEditText())){
						
						$EditTopicName = $this->ShowForumView->getFormTopicEditName();
						$EditTopicText = $this->ShowForumView->getFormTopicEditText();
						$this->db->EditTopic($EditTopicName,$EditTopicText,$this->ShowForumView->getTopicId(), $this->model->getLoggedInUser());
						$this->ShowForumView->successfulEdit();	
						
					}
					
					
					
				}
				elseif($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressEditTopic())
				{
					
					if($this->ShowForumView->didUserPressPostEditButtonTopic() && $this->db->checkIfIdIsManipulatedTopic($this->ShowForumView->getTopicId(), $this->model->getLoggedInUser()) && 
						$this->db->sanitizeString($this->ShowForumView->getFormTopicEditName()) && $this->db->sanitizeString($this->ShowForumView->getFormTopicEditText()) && 
						$this->db->CheckLenghtTopicName($this->ShowForumView->getFormTopicEditName()) &&
						$this->db->CheckLenghtTopicTextAndComment($this->ShowForumView->getFormTopicEditText())){

						$EditTopicName = $this->ShowForumView->getFormTopicEditName();
						$EditTopicText = $this->ShowForumView->getFormTopicEditText();
						$this->db->EditTopic($EditTopicName,$EditTopicText,$this->ShowForumView->getTopicId(), $this->model->getLoggedInUser());
						$this->ShowForumView->successfulEdit();
						

					}
				}
			}
			catch(Exception $e)
			{
				$this->ShowForumView->showMessage($e->getMessage());
			}
			$this->doHTMLBody();
				
		}

		public function doHTMLBody(){

			if($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressEditTopic())
			{
				
				$topics = $this->db->fetchTopic($this->ShowForumView->getTopicId());
				$this->ShowForumView->showEditTopicForm($topics);

			}
			

			
		}

	}