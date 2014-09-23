<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';

// change to client view if user is an admin
if(checkRole('admin')){
	$_SESSION['role'] = 'client';
}

// if an http referer is set, and doesn't point to the admin page, then go to it
if(isset($_SESSION['http_referer']) && strpos($_SESSION['http_referer'], 'admin.php') == FALSE){
	header('Location: ' . $_SESSION['http_referer']);
} else {
	// go to index.php (prevents bad role page from popping up if user was on admin page when they clicked the link here
	header('Location: index.php');
}