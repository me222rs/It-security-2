<?php
	require_once("common/HTMLView.php");
	require_once("LoginController.php");
	require_once("LoginModel.php");
	require_once("LoginView.php");
	require_once("ForumView.php");
	require_once("ShowTopicController.php");
	require_once("DeleteTopicController.php");
	require_once("CreateTopicController.php");
	require_once("EditTopicController.php");
	require_once("DeleteCommentController.php");
	
	class MasterController{
		private $model;
		private $view;
		private $forumView;
			
		public function __construct(){
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
						
			// Skapar nya instanser av modell- & vy-klassen och l�gger dessa i privata variabler.
			$this->model = new LoginModel($userAgent);
			$this->view = new LoginView($this->model);
			$this->forumView = new ForumView($this->model);
			
			echo "Kommer in i MasterController";
			var_dump($this->view->didUserPressLogin());
			//V�ljer vilken controller som ska anv�ndas beroende p� indata, t.ex. knappar och l�nkar.
			if(!$this->view->didUserPressLogin() && !$this->forumView->didUserPressCreateNewTopic() && !$this->forumView->didUserPressTopic() && !$this->forumView->didUserPressDeleteTopic() && !$this->forumView->didUserPressEditTopic()
				&& !$this->forumView->didUserPressDeleteComment())
			{
				echo "Kommer in i if";
				$loginC = new LoginController();
				$htmlBodyLogin = $loginC->doHTMLBody();
			}
			//Trycker på Show topics
			elseif($this->forumView->didUserPressTopic() && $this->model->checkLoginStatus()){
				//var_dump("Login status = " . $this->model->checkLoginStatus());
				echo "Tryckt på visa alla ämnen";
				
				$topicController = new ShowTopicController();
				$topicController->doHTMLBody();
				//$loginC = new LoginController();
				//$htmlBodyLogin = $loginC->doShowTopic();
				
			}
			
			elseif($this->forumView->didUserPressDeleteTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() == 1){
				//var_dump("Login status = " . $this->model->checkLoginStatus());
				echo "Tryckt på ta bort topic";
				
				$deleteTopicController = new DeleteTopicController();
				$deleteTopicController->doHTMLBody();

			}
			//När en roll försöker tas bort så kan endast användare med behörighet 1 eller lägre genomföra borttagningen
			elseif($this->forumView->didUserPressDeleteTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() > 1){
				echo "Tryckt på ta bort topic";
				
				$topicController = new ShowTopicController();
				$topicController->doHTMLBody();
				
			}
			//Trycker på Create new topic
			elseif($this->forumView->didUserPressCreateNewTopic() && $this->model->checkLoginStatus()){
				echo "Tryckt på skapa nytt ämne";
				$topicController = new CreateTopicController();
				$topicController->doHTMLBody();
				
			}
			
			elseif($this->forumView->didUserPressEditTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() == 1){
				
				$editTopicController = new EditTopicController();
				$editTopicController->doHTMLBody();
			}
			elseif($this->forumView->didUserPressEditTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() > 1){
				
				$topicController = new ShowTopicController();
				$topicController->doHTMLBody();
			}
			// elseif($this->forumView->didUserPressPostComment() && $this->model->checkLoginStatus()){
// 				
				// $topicController = new ShowTopicController();
				// $topicController->doHTMLBody();
			// }
			elseif($this->forumView->didUserPressDeleteComment() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() == 1){
				//var_dump("Login status = " . $this->model->checkLoginStatus());
				echo "Tryckt på ta bort topic";
				
				$deleteCommentController = new DeleteCommentController();
				$deleteCommentController->doHTMLBody();

			}

			
			
			else{
				echo "Kommer in i else";
				$loginControl = new LoginController();
				$htmlBodyLogin = $loginControl->doHTMLBody();
			}
			
			
			
		}
	}