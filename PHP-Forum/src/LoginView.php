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
				return $_POST['createusername'];
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
			// Variabler
			$weekDay = ucfirst(utf8_encode(strftime("%A"))); // Hittar veckodagen, till�ter �,�,� och g�r den f�rsta bokstaven stor.
			$month = ucfirst(strftime("%B")); // Hittar m�naden och g�r den f�rsta bokstaven stor.
			$year = strftime("%Y");
			$time = strftime("%H:%M:%S");
			$format = '%e'; // Fixar formatet s� att datumet anpassas f�r olika platformar. L�sning hittade p� http://php.net/manual/en/function.strftime.php
			// Kontrollerar inloggningsstatus. �r anv�ndaren inloggad...	
			if($this->model->checkLoginStatus())
			{				
				// ...visa anv�ndarsidan...
				$contentString = "
					$this->message
					
					<a href='?create'>Create new topic</a>
					<a href='?topics'>Show topics</a>
					
					<h2>Forum</h2>
					<p>Forum topic 1</p>
					<p>Forum topic 2</p>
					<p>Forum topic 3</p>
					
					<p><a href='?logout'>Logga ut</a></p>";
				$this->loginStatus = $this->model->getLoggedInUser() . " �r inloggad";
			}
			else 
			{
				
				
					// ...annars visas inloggningssidan.
					$this->loginStatus = "Ej inloggad";
					$contentString = 
					"<form id='loginForm' method=post action='?login'>
						<fieldset>
							<legend>Login - Skriv in anv�ndarnamn och l�senord</legend>
							$this->message
							Namn: <input type='text' name='$this->username' value='" . $this->getInputUsername() . "'>
							L�senord: <input type='password' name='$this->password'> 
							<input type='checkbox' name='$this->checkbox' value='checked'>H�ll mig inloggad:
							<button type='submit' name='button' form='loginForm' value='Submit'>Logga in</button>
						</fieldset>
					</form>";
				
			}
			
			// Kontrollerar ifall windowsformatet anv�nds och ers�tter %e med en fungerande del.
			if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
			{
    			$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
			}
			
			$HTMLbody = "
				<h1>Laboration 2 - ek222mw</h1>
				<h2>$this->loginStatus</h2>
				<p><a href='?register'>Registrera ny anv�ndare</a></p>
				$contentString
				" . strftime('' . $weekDay . ', den ' . $format . ' '. $month . ' �r ' . $year . '. Klockan �r [' . $time . ']') . ".";
			if($this->model->checkLoginStatus())
			{
			$HTMLbody = "
				<h1>Laboration 2 - ek222mw</h1>
				<h2>$this->loginStatus</h2>
				
				$contentString
				" . strftime('' . $weekDay . ', den ' . $format . ' '. $month . ' �r ' . $year . '. Klockan �r [' . $time . ']') . ".";
			}
			$this->echoHTML($HTMLbody);
		}
		public function showLoginPageWithRegname()
		{
			// Variabler
			$weekDay = ucfirst(utf8_encode(strftime("%A"))); // Hittar veckodagen, till�ter �,�,� och g�r den f�rsta bokstaven stor.
			$month = ucfirst(strftime("%B")); // Hittar m�naden och g�r den f�rsta bokstaven stor.
			$year = strftime("%Y");
			$time = strftime("%H:%M:%S");
			$format = '%e'; // Fixar formatet s� att datumet anpassas f�r olika platformar. L�sning hittade p� http://php.net/manual/en/function.strftime.php
			
			// Kontrollerar inloggningsstatus. �r anv�ndaren inloggad...	
			if($this->model->checkLoginStatus())
			{				
				// ...visa anv�ndarsidan...
				$contentString = "
					$this->message
					<p><a href='?logout'>Logga ut</a></p>";
				$this->loginStatus = $this->model->getLoggedInUser() . " �r inloggad";
			}
			else 
			{
					// ...annars visas inloggningssidan.
					$this->loginStatus = "Ej inloggad";
					$contentString = 
					"<form id='loginForm' method=post action='?login'>
						<fieldset>
							<legend>Login - Skriv in anv�ndarnamn och l�senord</legend>
							$this->message
							Namn: <input type='text' name='$this->username' value='" . $this->getRegisterUsername() . "'>
							L�senord: <input type='password' name='$this->password'>
						
							<input type='checkbox' name='$this->checkbox' value='checked'>H�ll mig inloggad:
							<button type='submit' name='button' form='loginForm' value='Submit'>Logga in</button>
						</fieldset>
					</form>";
				
			}
			
			// Kontrollerar ifall windowsformatet anv�nds och ers�tter %e med en fungerande del.
			if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
			{
    			$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
			}
			
			$HTMLbody = "
				<h1>Laboration 2 - ek222mw</h1>
				<h2>$this->loginStatus</h2>
				<p><a href='?register'>Registrera ny anv�ndare</a></p>
				$contentString
				" . strftime('' . $weekDay . ', den ' . $format . ' '. $month . ' �r ' . $year . '. Klockan �r [' . $time . ']') . ".";
			
			$this->echoHTML($HTMLbody);
		}
		public function showRegisterPage(){
			// Variabler
			$weekDay = ucfirst(utf8_encode(strftime("%A"))); // Hittar veckodagen, till�ter �,�,� och g�r den f�rsta bokstaven stor.
			$month = ucfirst(strftime("%B")); // Hittar m�naden och g�r den f�rsta bokstaven stor.
			$year = strftime("%Y");
			$time = strftime("%H:%M:%S");
			$format = '%e'; // Fixar formatet s� att datumet anpassas f�r olika platformar. L�sning hittade p� http://php.net/manual/en/function.strftime.php
			
			// Kontrollerar inloggningsstatus. �r anv�ndaren inloggad...	
			if($this->model->checkLoginStatus())
			{			
				
				// ...visa anv�ndarsidan...
				$contentString = "
					$this->message
					<p><a href='?logout'>Logga ut</a></p>";
				$this->loginStatus = $this->model->getLoggedInUser() . " �r inloggad";
			}else{
			// visa registreringssidan.
				
					 
					$this->loginStatus = "Ej inloggad, Registrerar anv�ndare";
					$contentString = 
					 "
					<form method=post >
						<fieldset>
							<legend>Registrera ny anv�ndare - Skriv in anv�ndarnamn och l�senord</legend>
							$this->message
							Namn: <input type='text' name='createusername' value='". strip_tags($_POST['createusername']) ."'><br>
							L�senord: <input type='password' name='createpassword'><br>
							Repetera L�senord: <input type='password' name='repeatpassword'><br>
							Skicka: <input type='submit' name='createuserbutton'  value='Registrera'>
						</fieldset>
					</form>";
					if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
					{
    					$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
					}
					$HTMLbody = "
				<h1>Laboration 2 - ek222mw</h1>
				
				<p><a href='?login'>Tillbaka</a></p>
				
				<h2>$this->loginStatus</h2>
				
				$contentString<br>
				" . strftime('' . $weekDay . ', den ' . $format . ' '. $month . ' �r ' . $year . '. Klockan �r [' . $time . ']') . ".";
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
			$this->showMessage("Inloggningen lyckades via cookies");
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
				return $_POST['username'];
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
			$this->showMessage("Inloggningen lyckades!");
		}
		
		// Visar login-meddelande f�r "H�ll mig inloggad"-funktionen.
		public function successfulLoginAndCookieCreation()
		{
			$this->showMessage("Inloggningen lyckades och vi kommer ih�g dig n�sta g�ng");
		}
		
		// Visar logout-meddelande.
		public function successfulLogout()
		{
			$this->showMessage("Du har nu loggat ut");
		}
		public function successfulRegistration()
		{
			$this->showMessage("Registrering av ny anv�ndare lyckades");
		}
		
	}
?>