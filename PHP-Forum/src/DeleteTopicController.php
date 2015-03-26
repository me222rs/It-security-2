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
			// Skapar nya instanser av modell- & vy-klasser och lägger dessa i privata variabler.
			$this->model = new LoginModel($userAgent);
			$this->ShowForumView = new ForumView($this->model);
			$this->db = new DBDetails();
			//$this->doHTMLBody();
		}
		//anropar vilken vy som ska visas.
		public function doHTMLBody()
		{
			if($this->ShowForumView->didUserPressDeleteTopic()){
				echo "Förbereder för att ta bort";

				//$topics = $this->db->fetchTopic($this->ShowForumView->getTopicId());
				
				$this->db->DeleteTopic($this->ShowForumView->getTopicId());
				//$comments = $this->db->fetchAllComments();

				$topics = $this->db->fetchAllTopics();
				$this->ShowForumView->ShowAllEventsWithBandGrades($topics);

				
				
			}

		}

	}