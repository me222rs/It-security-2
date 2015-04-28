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
	require_once("EditCommentController.php");
	require_once("ChangeCredentialsController.php");
	
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
			
			
			//V�ljer vilken controller som ska anv�ndas beroende p� indata, t.ex. knappar och l�nkar.
			if(!$this->view->didUserPressLogin() && !$this->forumView->didUserPressCreateNewTopic() && !$this->forumView->didUserPressTopic() && !$this->forumView->didUserPressDeleteTopic() && !$this->forumView->didUserPressEditTopic()
				&& !$this->forumView->didUserPressDeleteComment() && !$this->forumView->didUserPressEditComment() && !$this->forumView->didUserPressChangePassword())
			{
				
				$loginC = new LoginController();
				$htmlBodyLogin = $loginC->doHTMLBody();
			}
			//Trycker på Show topics
			elseif($this->forumView->didUserPressTopic() && $this->model->checkLoginStatus()){
				
				$topicController = new ShowTopicController();
				
				
			}
			
			elseif($this->forumView->didUserPressDeleteTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() == 1){
				
				
				$deleteTopicController = new DeleteTopicController();
				//$deleteTopicController->doHTMLBody();

			}
			//När en roll försöker tas bort så kan endast användare med behörighet 1 eller lägre genomföra borttagningen
			elseif($this->forumView->didUserPressDeleteTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() > 1){
				
				$deleteTopicController = new DeleteTopicController();
				//$topicController->doHTMLBody();
				
			}
			//Trycker på Create new topic
			elseif($this->forumView->didUserPressCreateNewTopic() && $this->model->checkLoginStatus()){
				
				$topicController = new CreateTopicController();
				
				
			}
			
			elseif($this->forumView->didUserPressEditTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() == 1){
				
				$editTopicController = new EditTopicController();
				
			}
			elseif($this->forumView->didUserPressEditTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() > 1){
				
				$editTopicController = new EditTopicController();
				
			}

			elseif($this->forumView->didUserPressEditTopic() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() > 1){
				
				$topicController = new ShowTopicController();
				
			}

			elseif($this->forumView->didUserPressDeleteComment() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() == 1){
				
				
				$deleteCommentController = new DeleteCommentController();
				

			}
			elseif($this->forumView->didUserPressDeleteComment() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() > 1){
				
				$deleteCommentController = new DeleteCommentController();
				

			}
			
			elseif($this->forumView->didUserPressEditComment() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() == 1){
				
				$editCommentController = new EditCommentController();
				
			}
			
			elseif($this->forumView->didUserPressEditComment() && $this->model->checkLoginStatus() && $this->model->getLoggedInUserRole() > 1){
				
				$topicController = new EditCommentController();
				
			}
			
			elseif($this->forumView->didUserPressChangePassword() && $this->model->checkLoginStatus()){
				
				$changeCredentialsController = new ChangeCredentialsController();
				
			}
			
			else{
				
				$loginControl = new LoginController();
				$htmlBodyLogin = $loginControl->doHTMLBody();
			}
			
			
			
		}
	}