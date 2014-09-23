<?php

// include our custom php functions
require_once '../functions.php';
require_once '../session.php';
require_once '../referer.php';

// get a reference to the resume information
$resumeInformation =& getSession();

// make sure the user is logged in as a client account
ensureLoggedIn('admin');

// validate any inputs that were posted to this page
validate();

// if post data is correct, delete the user. Show an error if the delete failed
// don't allow a user to delete their own account
if(isset($_POST['userid']) && $_POST['userid'] != $_SESSION['userID'])
	if(!deleteUser($_POST['userid']))
		$formface = addSubheading('Delete Failed');
	
// include getusers to get the new set of users after the delete
require 'getusers.php';