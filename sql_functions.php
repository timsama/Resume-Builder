<?php

// class definition for database backend
require_once 'databasebackend.php';

// adds a new user to the database
function addUser($username, $password, $realname){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// add the users to it and return the user ID
	return $db->insertUser($username, $password, $realname);
}

// logs a user in and returns their user id. returns 0 if it fails
function login($username, $password){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// return the user ID if username and password are correct
	return $db->login($username, $password);
}

// saves session variables into database
function saveSessionIntoDatabase(&$sourcevar){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// save the session data into the database
	return $db->saveResumeIntoDatabase($sourcevar);
}

// loads variables from database
function loadArrayFromDatabase($resumeid){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// load the session data from database
	return $db->getResumeAsArray($resumeid);
}

// returns an array of variables to preview a resume with
function previewResume($resumename, $username){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// load the preview data from database
	return $db->previewResumeAsArray($resumename, $username);
}

// returns a list of resumes
function getResumes($userid){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// get the list of resumes
	return $db->getResumes($userid);
}

// returns true if resumes exist for a user
function resumesExist($userid){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// return true if any resumes exist
	return $db->resumesExist($userid);
}

// deletes a specific resume for a user
function deleteResume($resumeid, $userid){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// return true if any resumes exist
	return $db->deleteResume($resumeid, $userid);
}

// returns an array of users and their ids, usernames, realnames, and roles
function getUsers(){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// return the array of users
	return $db->getUsers();
}

// updates a user's account role
function updateUserRole($userid, $role){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// return the array of users
	return $db->updateUserRole($userid, $role);
}

// deletes a user from the database
function deleteUser($userid){
	// create a database backend object
	$db = new DatabaseBackend();
	
	// return the array of users
	return $db->deleteUser($userid);
}