<?php

require_once("User.php");

	class UserList{


		private $users;

		//Lägger in privata varibeln users värden i en array.
		public function __construct(){

			$this->users = array();
		}

		//Lägger in, in parametern user i arrayen.
		public function add(User $user){

			$this->users[] = $user;

		}

		//Returnerar arrayen.
		public function toArray(){

			return $this->users;

		}


	}