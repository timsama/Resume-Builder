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

// if post data is correct, modify the user's role. Show an error if the update failed
// don't allow a user to update their own role
if(isset($_POST['userid']) && $_POST['userid'] != $_SESSION['userID'] 
	&& isset($_POST['role']) && ($_POST['role'] == 'admin' || $_POST['role'] == 'client'))
	if(!updateUserRole($_POST['userid'], $_POST['role']))
		$formface = addSubheading('Update Failed');

// include getusers to get the new set of users after the delete
require 'getusers.php';