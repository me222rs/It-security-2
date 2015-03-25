<?php
	Class Topic{
		private $m_name;
		private $m_id;
		private $m_text;
		private $m_owner;
		//Tilldelar privata variabler konstruktorns inv�rden.
		public function __construct($name,$id,$text,$owner){
			$this->m_name = $name;
			$this->m_id = $id;
			$this->m_text = $text;
			$this->m_owner = $owner;
			
		}
		//Returnerar namnet.
		public function getName(){
			return $this->m_name;
		}
		//Returnerar idet.
		public function getID(){
			return $this->m_id;
		}
		
		public function getText(){
			return $this->m_text;
		}
		
		public function getOwner(){
			return $this->m_owner;
		}
		
		
	}