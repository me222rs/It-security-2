<?php
	session_start();
	
	// Adderar ytterliggare s�kerhet g�llande sessionen.
	session_regenerate_id();
	ini_set('session.cookie_httponly', true);
	$httponly = true;
	
	// St�ller in sidans format s� att m�nad, �r, tid etc. visas p� svenska.
	setlocale(LC_ALL , "swedish");
	

	require_once("src/MasterController.php");
	
	$c = new MasterController();
	//$htmlBody = $c->doHTMLBody();
	
	
?>