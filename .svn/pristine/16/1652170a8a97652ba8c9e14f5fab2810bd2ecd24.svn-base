<?php

// start a new session
session_start();

// if no existing resume exists, create one for this session
if (! isset($_SESSION['resume'])){
	$_SESSION['resume'] = Array();
	$newResume = true;
} else {
	$newResume = false;
}