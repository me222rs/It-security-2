<?php
require_once("Topic.php");
	class TopicList{
		private $topics;
		//Lägger in privata varibeln topics värden i en array.
		public function __construct(){
			$this->topics = array();
		}
		//Lägger in, in parametern band i arrayen.
		public function add(Topic $topic){
			$this->topics[] = $topic;
		}
		//Returnerar arrayen.
		public function toArray(){
			return $this->topics;
		}
	}