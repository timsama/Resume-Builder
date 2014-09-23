<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';

// get a reference to the resume information
$resumeInformation =& getSession();

// make sure the user is logged in
ensureLoggedIn();

// save logged-in user's ID
$UID = $_SESSION['userID'];

// try to get resume ID
$resumeid = (isset($_POST['resumeid']) && (strlen(trim($_POST['resumeid'])) != 0)) ? $_POST['resumeid'] : 0;

// only load a new session if resume ID is valid
if($resumeid > 0){
	// load session data from the database
	unset($_SESSION['resume']);
	$_SESSION['resume'] = loadArrayFromDatabase($_POST['resumeid']);
	
	// re-get reference to the resume information
	$resumeInformation =& getSession();
	
	// restore the resume id
	$resumeInformation['resumeid'] = $resumeid;

	// load successful, display success message
	header('Location: save_load.php?loaded=yes');
} else {
	// load unsuccessful, display failure message
	header('Location: save_load.php?loaded=no');
}