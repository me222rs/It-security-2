<?php
	Class Comment{
		private $commentId;
		private $topicId;
		private $comment;
		private $commentPoster;
		//Tilldelar privata variabler konstruktorns inv�rden.
		public function __construct($commentId, $topicId, $comment, $commentPoster){
			$this->commentId = $commentId;
			$this->topicId = $topicId;
			$this->comment = $comment;
			$this->commentPoster = $commentPoster;
			
		}
		//Returnerar namnet.
		public function getCommentID(){
			return $this->commentId;
		}
		//Returnerar idet.
		public function getTopicID(){
			return $this->topicId;
		}
		
		public function getComment(){
			return $this->comment;
		}
		
		public function getCommentPoster(){
			return $this->commentPoster;
		}
		
		
	}