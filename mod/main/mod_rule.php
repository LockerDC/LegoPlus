<?php
///Авторизация
if(isset($_GET['login']) && isset($_GET['pass']))
{
	$_SESSION['login'] = $_GET['login'];
	$_SESSION['pass'] = $_GET['pass'];
}
?>
