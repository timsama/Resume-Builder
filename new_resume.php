<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';

// get a reference to the resume information
$resumeInformation =& getSession();

// clear resume session variables and then start fresh
$resumeInformation = array();

// set the resume as a new resume
$newResume = true;

// go to the start page
header('Location: index.php');