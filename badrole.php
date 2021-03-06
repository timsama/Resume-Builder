<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';

// initialize the form face
$formface = '';

// set the unauthorized user message
$formface .= addHeading('Unauthorized') . addParagraph('Your account is not authorized to view this resource.');

// if the http referer is set and doesn't point to the admin page, have the OK button take them to it. If not, have it take them to the index screen
if(isset($_SESSION['http_referer']) && strpos($_SESSION['http_referer'], 'admin.php') === FALSE){
	$formface .= startForm('error') . appendButton('OK', $_SESSION['http_referer']) . closeForm();
} else {
	$formface .= startForm('error') . appendButton('OK', 'index.php') . closeForm();
}


require 'template.php';