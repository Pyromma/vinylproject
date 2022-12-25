<?php
session_start();
//session_destroy();
if (!isset($_SESSION['loggedin']))
{
	header('Location: login.php');
	exit();
}
else
{
    header('Location: home.php');
	exit();
}

?>