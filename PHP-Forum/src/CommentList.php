<?php
require_once("Comment.php");
	class CommentList{
		private $comments;
		//Lägger in privata varibeln topics värden i en array.
		public function __construct(){
			$this->comments = array();
		}
		//Lägger in, in parametern band i arrayen.
		public function add(Topic $comment){
			$this->comments[] = $comment;
		}
		//Returnerar arrayen.
		public function toArray(){
			return $this->comments;
		}
	}