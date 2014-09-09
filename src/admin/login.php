<?php
/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 */

ob_start();
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	header("location: home.php");
	exit();
}
?>

<html>
<head>
	<title>Home - Admin | The Aberystwyth Web Evaluation Surveys Of Module Experiences</title>
		<link rel="icon" type="image/png" href="http://localhost/awesome/src//img/favicon.png">
		<meta name="viewport" content="width=device-width, initial-scale=1">
			<link href="http://localhost/awesome/src//css/bootstrap.min.css" rel="stylesheet">
		<link href="h../../css/custom.css" rel="stylesheet">

	<style>
		body {
  padding-top: 40px;
  padding-bottom: 40px;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
  text-align:center;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

	</style>

		
		<script src="http://localhost/awesome/src//js/jquery-1.11.0.min.js" type="text/javascript"></script>
		<script src="http://localhost/awesome/src//js/bootstrap.min.js" type="text/javascript"></script>
	</head>

<style>

</style>

<form class="form-signin" role="form" method="post">
		<img src="http://aberawesome.co.uk/img/logo.png" width="300">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input class="form-control" placeholder="Username" required="" autofocus="" type="text">
        <input class="form-control" placeholder="Password" required="" type="password">
        <label class="checkbox">
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit" value="Login">Sign in</button>
      </form>

</html>
