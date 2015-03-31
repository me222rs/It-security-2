<?php
	
	require_once("ForumView.php");
	require_once("DBDetails.php");
	require_once 'LoginModel.php';
	class ChangeCredentialsController{
		
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
			$this->doControll();
		}
		//anropar vilken vy som ska visas.
		public function doControll()
		{
			try
			{
				if($this->ShowForumView->didUserPressChangePassword()){
					if($this->ShowForumView->didUserPressPostNewPassword()){
						if($this->db->sanitizeString($this->ShowForumView->getCurrentPassword())){
						if($this->model->verifyChangeUserInput($this->model->getLoggedInUser(), $this->ShowForumView->getCurrentPassword())){
							echo "User Exists";
							if($this->model->ComparePasswordRepPassword($this->ShowForumView->getNewPassword(),$this->ShowForumView->getNewRepeatPassword())){
								echo "passwords match";
								$this->db->ChangePassword($this->model->getLoggedInUser(), $this->ShowForumView->getCurrentPassword(), $this->ShowForumView->getNewPassword());
							}
								
								//$this->db->ChangePassword($this->model->getLoggedInUser(), $this->ShowForumView->getCurrentPassword(), $this->ShowForumView->getNewPassword());
							}
						}
					}
				}
			}
			catch(Exception $e)
			{
				$this->ShowForumView->showMessage($e->getMessage());
			}
			$this->doHTMLBody();
			
		}

		public function doHTMLBody()
		{
				if($this->ShowForumView->didUserPressChangePassword()){
					$this->ShowForumView->ChangePasswordForm();
				}
		}

	}