<?php
require_once 'common/HTMLView.php';
	class LoginView extends HTMLView
	{
		private $model;
		private $loginStatus = "";
		private $username = "username";
		
		private $password = "password";
		
		private $checkbox = "checkbox";
		private $message = "";
		
		public function __construct(LoginModel $model)
		{
			$this->model = $model;
		}
		
		// Kontrollerar ifall anv�ndarnamnet �r lagrat i POST-arrayen.
		public function didUserPressLogin()
		{
			return isset($_POST[$this->username]);
		}
		
		// Kontrollerar ifall URL:en inneh�ller logout.
		public function didUserPressLogout()
		{
			return isset($_GET['logout']);
		}
		
		public function didUserPressRegister()
		{
			return isset($_GET['register']);
		}
		public function didUserPressCreateUser(){
			if(isset($_POST['createuserbutton']))
			{
				return true;
			}
			return false;
		}		
		public function getRegisterUsername(){
			if(isset($_POST['createusername']))
			{
				return htmlspecialchars($_POST['createusername']);
			}
			return false;
		}
		public function getRegisterPassword(){
			if(isset($_POST['createpassword']))
			{
				return md5("45gt4ad". $_POST['createpassword'] . "55uio11");
			}
			return false;
		}
		public function getRepeatRegisterPassword(){
			if(isset($_POST['repeatpassword']))
			{
				return md5("45gt4ad". $_POST['createpassword'] . "55uio11");
			}
			return false;
		}
		
		// S�tter body-inneh�llet.
		public function showLoginPage()
		{
			
			// Kontrollerar inloggningsstatus. �r anv�ndaren inloggad...	
			if($this->model->checkLoginStatus())
			{				
				// ...visa anv�ndarsidan...
				$contentString = "
					$this->message
					
					<a href='?create'>Create new topic</a>
					<a href='?topics'>Show topics</a>
					<a href='?changepw'>Change password</a>
					<p><a href='?logout'>Logout</a></p>";
				$this->loginStatus = $this->model->getLoggedInUser() . " Is logged in";
			}
			else 
			{
				
					// ...annars visas inloggningssidan.
					$this->loginStatus = "Not logged in";
					$contentString = 
					"<form id='loginForm' method=post action='?login'>
						<fieldset>
							<legend>Login - Write username and password</legend>
							$this->message
							Name: <input type='text' name='$this->username' value='" . $this->getInputUsername() . "'>
							Password: <input type='password' name='$this->password'><br> 
							<div class='g-recaptcha' data-sitekey='6LdK9AMTAAAAABnYjmV2ZlSrdicAtpcqsxF7mX_M'></div><br>
							<button type='submit' name='button' form='loginForm' value='Submit'>Login</button>
						</fieldset>
					</form>";
				
			}
			
			
			
			$HTMLbody = "
				<div><h1>It Security</h1>
				<h2>$this->loginStatus</h2>
				<p><a href='?register'>Register new user</a></p>
				$contentString</div>
				";
			if($this->model->checkLoginStatus())
			{
				$HTMLbody = "
				<div>
				<h1>It Security</h1>
				<h2>$this->loginStatus</h2>
				$contentString</div>
				";
			}

			$this->echoHTML($HTMLbody);
		}
		public function showLoginPageWithRegname()
		{
			
			
			// Kontrollerar inloggningsstatus. �r anv�ndaren inloggad...	
			if($this->model->checkLoginStatus())
			{				
				// ...visa anv�ndarsidan...
				$contentString = "
					$this->message
					<p><a href='?logout'>Logout</a></p>";
				$this->loginStatus = $this->model->getLoggedInUser() . " is logged in";
			}
			else 
			{
					// ...annars visas inloggningssidan.
					$this->loginStatus = "Not logged in";
					$contentString = 
					"<form id='loginForm' method=post action='?login'>
						<fieldset>
							<legend>Login - Write username and password</legend>
							$this->message
							Name: <input type='text' name='$this->username' value='" . $this->getRegisterUsername() . "'>
							Password: <input type='password' name='$this->password'><br><br>
							<div class='g-recaptcha' data-sitekey='6LdK9AMTAAAAABnYjmV2ZlSrdicAtpcqsxF7mX_M'></div><br>
							<button type='submit' name='button' form='loginForm' value='Submit'>Login</button>
						</fieldset>
					</form>";
				
			}
			
			
			$HTMLbody = "
				<div>
				<h1>It Security</h1>
				<h2>$this->loginStatus</h2>
				<p><a href='?register'>Register new user</a></p>
				$contentString</div>
				";
			
			$this->echoHTML($HTMLbody);
		}
		public function showRegisterPage(){
			
			
			// Kontrollerar inloggningsstatus. �r anv�ndaren inloggad...	
			if($this->model->checkLoginStatus())
			{			
				
				// ...visa anv�ndarsidan...
				$contentString = "
					$this->message
					<p><a href='?logout'>Logout</a></p>";
				$this->loginStatus = $this->model->getLoggedInUser() . " is logged in";
			}else{
			// visa registreringssidan.
				
					 
					$this->loginStatus = "Not logged in, register new user";
					$contentString = 
					 "
					<form class='loginForm' method=post >
						<fieldset>
							<legend>Register new user - Write username and password</legend>
							$this->message
							Name:<br> <input type='text' name='createusername' value='". htmlspecialchars($_POST['createusername']) ."'><br>
							Password:<br> <input type='password' name='createpassword'><br>
							Repeat Password:<br> <input type='password' name='repeatpassword'><br>
							<input type='submit' name='createuserbutton'  value='Register'>
						</fieldset>
					</form>";
					$HTMLbody = "
					<div>	
					<h1>It Security</h1>
					<h2>$this->loginStatus</h2>
					<p><a href='?login'>Back</a></p>
					$contentString<br></div>
					";
					$this->echoHTML($HTMLbody);
			}
				
		}
		
		// Skapar cookies inneh�llande de medskickande v�rdena.
		public function createCookies($usernameToSave, $passwordToSave)
		{
			// Best�mmer cookies livsl�ngd.
			$cookieExpirationTime = time()+ 60;
			
			// Skapar cookies.
			setcookie("Username", $usernameToSave, $cookieExpirationTime);
			setcookie("Password", $passwordToSave, $cookieExpirationTime);
			
			//Skapar en fil inneh�llande information om kakornas livsl�ngd.
			$this->model->createReferenceFile($cookieExpirationTime, "cookieExpirationTime");
		}
		
		public function searchForCookies()
		{
			if(isset($_COOKIE["Username"]) === true && isset($_COOKIE["Password"]) === true)
			{
				return true;
			}
			
			return false;
		}
		
		// Logga in med kakor.
		public function loginWithCookies()
		{
			// Validera cookies mot textfilen.
			$this->model->validateExpirationTime();
			
			// Validera kakornas inneh�ll.
			$this->model->verifyUserInput($_COOKIE["Username"], $this->model->decodePassword($_COOKIE["Password"]), true);
			
			// Visa r�ttmeddelande.
			$this->showMessage("Login succeed");
		}
		
		// Tar bort alla cookies.
		public function removeCookies()
		{
			foreach ($_COOKIE as $c_key => $c_value)
			{
				setcookie($c_key, NULL, 1);
			}
		}
		
		// Sparar angivet anv�ndarnamn i textf�ltet.
		public function getInputUsername()
		{
			if(isset($_POST['username']))
			{
				return htmlspecialchars($_POST['username']);
			}
			
			// �r inte anv�ndarnamnet satt skickas en tomstr�ng med.
			return "";
		}
		
		// Visar eventuella meddelanden.
		public function showMessage($message)
		{
			$this->message = "<p>" . $message . "</p>";
		}
		
		// Visar login-meddelande.
		public function successfulLogin()
		{
			$this->showMessage("Login succeed!");
		}
		
		
		// Visar login-meddelande f�r "H�ll mig inloggad"-funktionen.
		public function successfulLoginAndCookieCreation()
		{
			$this->showMessage("Login succeed, we remember you next time");
		}
		
		// Visar logout-meddelande.
		public function successfulLogout()
		{
			$this->showMessage("You have logged out");
		}
		public function successfulRegistration()
		{
			$this->showMessage("Registering new user succeed");
		}
		
	}
?>