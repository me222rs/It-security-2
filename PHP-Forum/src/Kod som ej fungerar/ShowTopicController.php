<?php
	
	require_once("./src/ForumView.php");
	require_once("./src/DBDetails.php");
	class ShowTopicController{
		
		private $ShowForumView;
		private $db;
		public function __construct(){
			// Skapar nya instanser av modell- & vy-klasser och lägger dessa i privata variabler.
			$this->ShowForumView = new ForumView();
			$this->db = new DBDetails();
			$this->doHTMLBody();
		}
		//anropar vilken vy som ska visas.
		public function doHTMLBody()
		{
			
			$topics = $this->db->fetchAllTopics();
			$this->ShowForumView->ShowAllEventsWithBandGrades($topics);
		}
	}