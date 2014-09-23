<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';

// initialize form face
$formface = '';

// add page heading
$formface .= addHeading('Register New Account');
$formface .= addParagraph('Username and Password must be at least 8 characters long');

// validate username and password
if(validate()){
	// move to the new resume page
	header('Location: new_resume.php');
} else {
	// display an entry prompt depending on whether this is a first or subsequent attempt
	if(registrationAttempted()){
		// inform user their account was not added successfully
		$formface .= addSubheading('Username is taken or invalid. Please enter a different username and a password:');
	} else {
		$formface .= addSubheading('Please enter a username and a password:');
	}

	// start the HTML form
	$formface .= startForm('register.php');
	
	// add hidden field to store this page as the page we came from
	$formface .= addHiddenField('sourceurl', 'register.php');
	
	// add user name and password fields
	$formface .= addParagraph(addTextField('username', 'User Name: ', '', '', '', 'min8') . '<br>' . addPasswordField('password', 'Password: ', 'password', 'min8') . '<br>' . addTextField('realname', 'Real Name: '));
	
	// add submit and cancel buttons
	$formface .= appendSubmitButton();
	$formface .= appendCancelButton();
}

// end the HTML form
$formface .= closeForm();

require 'template.php';