<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';

// redirect to a secure connection
redirectToHTTPS();

// set a new session
changeSessionID();

// set up URL parameters to save referer
$urlquery = array('referer' => $_GET['referer']);

// go to the login page
header('Location: login.php?' . http_build_query($urlquery));