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
			//$this->doHTMLBody();
		}
		//anropar vilken vy som ska visas.
		public function doHTMLBody()
		{
			if($this->ShowForumView->didUserPressThread()){
				echo "Visa en tråd";
				//$this->ShowForumView->getTopicId();
				
				//Hämta rad i databasen som har samma id som man klickade på
				//Hämta rader i databasen med kommentarer som tillhör id
				//Skicka med dessa till showtopics
				$topics = $this->db->fetchTopic($this->ShowForumView->getTopicId());
				$comments = $this->db->fetchAllComments($this->ShowForumView->getTopicId());
				//$comments = $this->db->fetchAllComments();
				//var_dump($comments);
				$this->ShowForumView->showTopics($topics, $comments);
			}
			else{
				echo "Kommer in i doHTMLBody";
				$topics = $this->db->fetchAllTopics();
				$this->ShowForumView->ShowAllEventsWithBandGrades($topics);
			}
		}

	}