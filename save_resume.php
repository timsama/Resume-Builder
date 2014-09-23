<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';

// get a reference to the resume information
$resumeInformation =& getSession();

// make sure the user is logged in
ensureLoggedIn();

// add a name to the resume if user entered one
if(getResumeVar('resumename') == ''){
	if(getPostVar('resume_name') != ''){
		$resumeInformation['resumename'] = getPostVar('resume_name');
	} else {
		// report that save failed
		header('Location: save_load.php?saved=no');
	}
}

// save the session data to the database
if(saveSessionIntoDatabase($resumeInformation)){
	// report that save succeeded
	header('Location: save_load.php?saved=yes');
} else {
	// report that save failed
	header('Location: save_load.php?saved=no');
}