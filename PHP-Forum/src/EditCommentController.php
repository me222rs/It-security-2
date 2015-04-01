<?php
	
	require_once("ForumView.php");
	require_once("DBDetails.php");
	require_once 'LoginModel.php';
	class EditCommentController{
		
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
				if($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressEditComment() && $this->model->getLoggedInUserRole() == 1){
				
				
					if($this->ShowForumView->didUserPressPostEditButtonComment() && $this->model->getLoggedInUser() && 
					$this->db->sanitizeString($this->ShowForumView->getFormCommentEditText())){
						
						$EditCommentText = $this->ShowForumView->getFormCommentEditText();
						

						$this->db->EditComment($EditCommentText, $this->ShowForumView->getCommentId(), $this->model->getLoggedInUser());
						$this->ShowForumView->successfulEdit();
						
					}
				}
			
				else{
				
				
					
					if($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressEditComment()){
						
						$comments = $this->db->fetchComment($this->ShowForumView->getCommentId());
					
					
						if($this->ShowForumView->didUserPressPostEditButtonComment() && $this->model->getLoggedInUser() && 
						$this->db->checkIfIdIsManipulated($this->ShowForumView->getCommentId(), $this->model->getLoggedInUser()) && 
						$this->db->sanitizeString($this->ShowForumView->getFormCommentEditText())){
		
							$EditCommentText = $this->ShowForumView->getFormCommentEditText();
							
							
							$this->db->EditComment($EditCommentText, $this->ShowForumView->getCommentId(), $this->model->getLoggedInUser());
							$this->ShowForumView->successfulEdit();
							
						}
					}
				}
				}
				catch(Exception $e){
					
					$this->ShowForumView->showMessage($e->getMessage());
				}
				

			
			$this->doHTMLBody();
				
		}

		public function doHTMLBody(){
			
			if($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressEditComment() && $this->model->getLoggedInUserRole() == 1)
			{
				
				$comments = $this->db->fetchComment($this->ShowForumView->getCommentId());
				$this->ShowForumView->showEditCommentForm($comments);
			}
			
			elseif($this->model->checkLoginStatus() && $this->ShowForumView->didUserPressEditComment())
			{
				
				$comments = $this->db->fetchComment($this->ShowForumView->getCommentId());
				$this->ShowForumView->showEditCommentForm($comments);
			}
		}

	}

