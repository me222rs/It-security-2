

	<?php


	Class User{

		private $m_user;
		private $m_id;
		private $m_failLogins;

		//Tilldelar privata variabler konstruktorns invärden.
		public function __construct($user,$id,$failLogins){
			$this->m_user = $user;
			$this->m_id = $id;
			$this->m_failLogins = $failLogins;


		}

		//Returnerar namnet.
		public function getName(){

			return $this->m_user;
		}

		//Returnerar idet.
		public function getID(){

			return $this->m_id;
		}

		public function getFails(){

			return $this->m_failLogins;
		}
	}