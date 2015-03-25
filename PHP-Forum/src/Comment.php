<?php
	Class Comment{
		private $commentId;
		private $topicId;
		private $comment;
		private $commentPosterId;
		//Tilldelar privata variabler konstruktorns inv�rden.
		public function __construct($commentId, $topicId, $comment, $commentPosterId){
			$this->$commentId = $commentId;
			$this->$topicId = $topicId;
			$this->$comment = $comment;
			$this->$commentPosterId = $commentPosterId;
			
		}
		//Returnerar namnet.
		public function getCommentID(){
			return $this->$commentId;
		}
		//Returnerar idet.
		public function getTopicID(){
			return $this->$topicId;
		}
		
		public function getComment(){
			return $this->$comment;
		}
		
		public function getcommentPosterID(){
			return $this->$commentPosterId;
		}
		
		
	}