<?php
ob_start();
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$_SESSION["admin_user"] = "a";
}
 
 if ($_SESSION["admin_user"]) {
	header("location: home.php");
	exit();
}

print_r($_SESSION);

?>

<form method="post">
	<input type="submit" value="Login">
</form>
