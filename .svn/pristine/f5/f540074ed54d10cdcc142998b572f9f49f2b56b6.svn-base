<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';

// if there is an HTTP referer, add it to the session
if(isset($_SERVER['HTTP_REFERER']))
	$_SESSION['http_referer'] = $_SERVER['REQUEST_URI'];