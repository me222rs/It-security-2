<?php
	
	require_once("ForumView.php");
	require_once("DBDetails.php");
	require_once 'LoginModel.php';
	class DeleteCommentController{
		
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
			
				if($this->ShowForumView->didUserPressDeleteComment() && $this->model->getLoggedInUserRole() == 1 && $this->model->checkLoginStatus()){
	
					//Tar bort en specifik topic med hjälp av ett id
					$this->db->DeleteComment($this->ShowForumView->getCommentID(), $this->model->getLoggedInUser());	
	
				}
				elseif($this->ShowForumView->didUserPressDeleteComment() && $this->db->checkIfIdIsManipulated($this->ShowForumView->getCommentId(), $this->model->getLoggedInUser()) && 
				$this->model->checkLoginStatus())
				{
					$this->db->DeleteComment($this->ShowForumView->getCommentID(), $this->model->getLoggedInUser());
				}
					
				
			}
			catch(Exception $e)
			{
				$this->ShowForumView->showMessage($e->getMessage());
			}
			$this->doHTMLBody();

		}
		
		//anropar vilken vy som ska visas.
		public function doHTMLBody()
		{
			if($this->ShowForumView->didUserPressDeleteComment() && $this->model->checkLoginStatus()){
				$topics = $this->db->fetchAllTopics();
				$this->ShowForumView->ShowAllTopics($topics);
				
			}

		}

	}