<?php
	
	require_once("ForumView.php");
	require_once("DBDetails.php");
	require_once 'LoginModel.php';
	class DeleteTopicController{
		
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
			try{
				if($this->ShowForumView->didUserPressDeleteTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() == 1){
					
					//Tar bort en specifik topic med hjälp av ett id
					$this->db->DeleteTopic($this->ShowForumView->getTopicId(), $this->model->getLoggedInUser());
					$this->ShowForumView->successfulDeletedTopic();
					
	
					
				}
				else
				{
					$this->db->LogAction($this->model->getLoggedInUser(), "Failed to delete topic with id " . $this->ShowForumView->getTopicId());
					throw new Exception("You don't have rights to do this");
				}
			}
			catch(Exception $e)
			{
					
				$this->ShowForumView->showMessage($e->getMessage());
			}
			$this->doHTMLBody();

				
				
			

		}
		public function doHTMLBody()
		{
			if($this->ShowForumView->didUserPressDeleteTopic() && $this->model->checkLoginStatus()){
				//När topic har tagits bort så visas alla topics
				$topics = $this->db->fetchAllTopics();
				$this->ShowForumView->ShowAllTopics($topics);

				
				
			}

		}

	}