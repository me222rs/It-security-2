<?php
	
	class HTMLView
	{
		public function echoHTML($body)
		{
		    
			echo "
				<!DOCTYPE html>
				<html lang='sv'>
				<head>
				<meta charset='ISO-8859-4'>
				<title>Labb 4</title>
				<link rel='stylesheet'type='text/css' href='Css/bootstrap.css' media='screen and (min-width:481px)' />
        		<link rel='stylesheet' type='text/css' href='Css/bootstrap.css' media='screen and (max-width:481px)' />
       			<link rel='stylesheet' type='text/css' href='css/bootstrap.css' media='print' />
				<script src='https://www.google.com/recaptcha/api.js'></script>
				</head>
				<body>
						$body
					</body>
				</html>";
		}
	}
?>