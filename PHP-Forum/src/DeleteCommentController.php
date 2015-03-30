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

		}
		//anropar vilken vy som ska visas.
		public function doHTMLBody()
		{
			if($this->ShowForumView->didUserPressDeleteComment()){

				//Tar bort en specifik topic med hjälp av ett id
				$this->db->DeleteComment($this->ShowForumView->getCommentID(), $this->model->getLoggedInUser());
				//$comments = $this->db->fetchAllComments();

				//När topic har tagits bort så visas alla topics
				$topics = $this->db->fetchAllTopics();
				$this->ShowForumView->ShowAllEventsWithBandGrades($topics);

				
				
			}

		}

	}