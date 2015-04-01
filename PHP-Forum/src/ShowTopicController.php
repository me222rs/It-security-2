<?php
	
	require_once("ForumView.php");
	require_once("DBDetails.php");
	require_once 'LoginModel.php';
	class ShowTopicController{
		
		private $ShowForumView;
		private $db;
		private $model;
		
		
		public function __construct(){
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
			// Skapar nya instanser av modell- & vy-klasser och lägger dessa i privata variabler.
			$this->model = new LoginModel($userAgent);
			$this->ShowForumView = new ForumView($this->model);
			$this->db = new DBDetails();
			
			$this->doControll();
		}
		
		public function doControll()
		{
			try
			{
				if($this->ShowForumView->didUserPressThread() && $this->model->checkLoginStatus()){
				
				
				//Hämta rad i databasen som har samma id som man klickade på
				//Hämta rader i databasen med kommentarer som tillhör id
				//Skicka med dessa till showtopics
				
				
				if($this->ShowForumView->didUserPressPostComment() && $this->db->sanitizeString($this->ShowForumView->getFormCommentText()) && $this->model->checkLoginStatus()){
					//$topicName = $this->ShowForumView->getFormTopicName();
					$commentText = $this->ShowForumView->getFormCommentText();
					
					$this->db->createComment($commentText, $this->ShowForumView->getTopicId(), $this->model->getLoggedInUser());
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

		public function doHTMLBody()
		{
			if($this->ShowForumView->didUserPressThread() && $this->model->checkLoginStatus()){
				
				$topics = $this->db->fetchTopic($this->ShowForumView->getTopicId());
				$comments = $this->db->fetchAllComments($this->ShowForumView->getTopicId());
				$this->ShowForumView->showTopics($topics, $comments);
				
				if($this->ShowForumView->didUserPressPostComment()){
					
					
				}
			}
			else{
				
				if($this->model->checkLoginStatus())
				{
					$topics = $this->db->fetchAllTopics();
					$this->ShowForumView->ShowAllTopics($topics);
				}
			}
		}

	}