<?php
	require_once("common/HTMLView.php");
	require_once("LoginController.php");
	require_once("LoginModel.php");
	require_once("LoginView.php");
	require_once("ForumView.php");
	require_once("ShowTopicController.php");
	
	
	class MasterController{
		private $model;
		private $view;
		
		private $forumView;
		private $showTopicController;
			
		public function __construct(){
			
						
			// Skapar nya instanser av modell- & vy-klassen och l�gger dessa i privata variabler.
			$this->model = new LoginModel();
			$this->view = new LoginView($this->model);
			$this->forumView = new ForumView($this->model);
			
			var_dump($this->forumView->didUserPressShowTopics());
			var_dump($this->model->checkLoginStatus());
			
			//V�ljer vilken controller som ska anv�ndas beroende p� indata, t.ex. knappar och l�nkar.
			if(!$this->view->didUserPressLogin() && !$this->forumView->didUserPressShowTopics() && $this->model->checkLoginStatus())
			{
				echo "1";
				$loginC = new LoginController();
				$htmlBodyLogin = $loginC->doHTMLBody();
			}
			
			else if($this->forumView->didUserPressShowTopics() && $this->model->checkLoginStatus()){
				
				echo "2";
				$this->showTopicController = new ShowTopicController();
			}

			else{
				echo "test3";
				$loginControl = new LoginController();
				$htmlBodyLogin = $loginControl->doHTMLBody();
				
			}
			
			
			
		}
	}