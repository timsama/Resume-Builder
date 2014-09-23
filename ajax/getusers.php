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

// initialize form face
$formface = (isset($formface)) ? $formface : '';

// add users table
$userarray = getUsers();
$formface .= addUsersTable($userarray, getSessionVar('userID'));

// display users table for AJAX to pick up and insert into the page
echo $formface;