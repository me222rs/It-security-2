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
				<script src='https://www.google.com/recaptcha/api.js'></script>
				</head>
				<body>
						$body
					</body>
				</html>";
		}
	}
?>