<?php

// include error logging configuration
require_once 'errors.php';

// include database functions
require_once 'sql_functions.php';

/* Copied from Jim's GSCDB example */
// Changes the session ID
function changeSessionID () {
	
	// Ask the browser to delete the existing cookie
	setcookie("PHPSESSID", "", time()-3600, "/");

	// Change the session ID and send it to the browser in a secure cookie
	$server = $_SERVER['SERVER_NAME'];
	$secure = usingHTTPS();
	session_set_cookie_params(0, "/", $server, $secure, true);
	session_regenerate_id(true);
}

/* Copied from Jim's GSCDB example */
// Reports if https is in use
function usingHTTPS () {
	return isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != "off");
}

/* Copied from Jim's GSCDB example */
// Redirects to HTTPS
function redirectToHTTPS()
{	
	if(!usingHTTPS())
	{		
		$redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("Location:$redirect");
		exit();
	}
}

/* Adapted from Jim's GSCDB example */
// Redirects to HTTP
function redirectToHTTP()
{	
	if(usingHTTPS())
	{		
		$redirect = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("Location:$redirect");
		exit();
	}
}


// inserts the stylesheet
function insertStyles(){
	return '<link rel="stylesheet" type="text/css" href="style.css" />';
}

// inserts the title image on all pages except the resume
function insertTitle(){
	if(strpos($_SERVER['REQUEST_URI'], 'resume.php') === FALSE){
		return '<img src="images/title.png"/>';
	} else {
		return '';
	}
}

// inserts required scripts
function insertScripts(){
	$temp = '<script src="scripts/jquery-2.0.3.min.js" type="text/javascript"></script>';
	$temp .= '<script src="scripts/validation.js" type="text/javascript"></script>';
	
	// if on an admin page with admin privileges, include admin function scripts as well
	if(checkRole('admin')){
		$temp .= '<script src="ajax/admin_functions.js" type="text/javascript"></script>';
	}
	
	return $temp; 
}

// inserts the footer into the page
function insertFooter(){
	require 'footer.php';
}

// returns a reference to the current resume session, creating one if needed
function &getSession(&$newResume = false){
	// if no existing resume exists, create one for this session
	if (! isset($_SESSION['resume'])){
		$_SESSION['resume'] = Array();
		$newResume = true;
	} else {
		$newResume = false;
	}
	
	// return a reference to the resume information
	return $_SESSION['resume'];
}

// boots the user to the bad role page if they are not qualified to access the page
function ensureLoggedIn($role = ''){
	// if no user ID is defined, they should not have access
	if(! isLoggedIn()){
		// initialize the url query to be blank
		$urlquery = array();
		
		// if set, allow user to return to this page after logging in
		if(isset($_SERVER['REQUEST_URI'])){
			// set up URL parameters to save referer
			$urlquery = array('referer' => $_SERVER['REQUEST_URI']);
		}
		
		header('Location: login.php?' . http_build_query($urlquery));
		return false;
	}
	
	// if they are logged in, check to make sure they have the correct role to access the page
	if(! checkRole($role)){
		header('Location: badrole.php');
		return false;
	}
	
	// they were logged in and had the correct role, return true
	return true;
}

// checks to see if the user is logged in
function isLoggedIn(){
	// get the current session
	$resumeInformation =& getSession();
	
	// if no user ID is defined, they're not logged in
	return isset($_SESSION['userID']) || isset($UID);
}

// returns true if the current user's role has sufficient permissions to do something
function checkRole($requiredrole){
	// if there is no required role, then all permissions are sufficient 
	if($requiredrole == '')
		return true;
	
	// otherwise, a role needs to be set. If it isn't, then permissions can't be sufficient
	if(!isset($_SESSION['role']))
		return false;
	
	// if the user is an admin, then they always have sufficient permissions
	if($_SESSION['role'] == 'admin')
		return true;
	
	// if a role is required and the user is not an admin, then compare the required role to the user's role
		return $_SESSION['role'] == $requiredrole;
}

/* functions to return form completeness */
// returns true if the given form is complete
function formIsComplete($formvarname){
	// get the current session
	$resumeInformation =& getSession();
	
	// return true if the variable exists and is set to true
	return in_array($formvarname, $resumeInformation) && isset($resumeInformation[$formvarname]) && $resumeInformation[$formvarname];
}

// returns either contents of an element (if it exists) or an empty string (if it does not)
function getPostVar($name){	
	// if the variable exists and is set to a value, return it
	if(isset($_POST[$name])){
		return $_POST[$name];
	}
	
	// variable did not exist, or was not set. return an empty string
	return '';
}

// returns either contents of a session variable (if it exists) or an empty string (if it does not)
function getSessionVar($name){
	
	// if the variable exists and is set to a value, return it
	if(isset($_SESSION[$name])){
		return $_SESSION[$name];
	}
	
	// variable did not exist, or was not set. return an empty string
	return '';
}

// returns either contents of an element (if it exists) or an empty string (if it does not)
function getResumeVar($name, $source = ''){
	// if no source is specified, get the current session
	if($source == ''){
		$source =& getSession();
	}
	
	// if the variable exists and is set to a value, return it
	if(isset($source[$name])){
		return $source[$name];
	}
	
	// variable did not exist, or was not set. return an empty string
	return '';
}

// returns either contents of an array element (if it exists) or an empty string (if it does not)
function getResumeArrayVar($name, $index, $source = ''){
	// if no source is specified, get the current session
	if($source == ''){
		$source =& getSession();
	}
	
	// if the variable exists and is set to a value, return it
	if(isset($source[$name])){
		$temparray = $source[$name];
		return $temparray[$index];
	}
	
	// variable did not exist, or was not set. return an empty string
	return '';
}

// returns either length of an array element (if it exists) or 0 (if it does not)
function getResumeArrayLength($name, $source = ''){
	// if no source is specified, get the current session
	if($source == ''){
		$source =& getSession();
	}
	
	// if the variable exists and is set to a value, return it
	if(isset($source[$name])){
		$temparray = $source[$name];
		
		return count($temparray);
	}
	
	// variable did not exist, or was not set. return an empty string
	return 0;
}

/* general HTML-appending functions */
// adds an HTML element to a form if one is specified, returns a string containing the HTML
function addElement($contents, &$form = NULL){
	// if a form was given, append the element
	if($form !== NULL){
		$form .= $contents;
	}
	
	// return the element as a string
	return $contents;
}

// adds a hidden form input
function addHiddenField($name, $value = '', &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
	
	// add the element to the form
	return addElement($temp, $form);
}

// starts a new HTML form
function startForm($thisformname = '', $validationScript = 'validate()', &$form = NULL){
	// create the opening form tag
	$temp = '<form action="' . $thisformname . '" method="post" onsubmit="return ' . $validationScript . '">';
	
	// add the element to the form
	return addElement($temp, $form);
}

// closes an HTML form
function closeForm(&$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '</form>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// returns a submit button
function appendSubmitButton(&$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<input type="submit" value="Submit" />';
	
	// add the element to the form
	return addElement($temp, $form);
}

// returns a button
function appendButton($value, $url, $onclick = 'return true;', &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<button formaction=' . $url . ' onclick="' . $onclick . '">' . $value . '</button>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// returns a cancel button
function appendCancelButton(&$form = NULL){
	// set a temp variable to hold the string to append/return
	$url = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'contact_information.php';
	
	$temp = '<input type="button" value="Cancel" onclick="goToURL(\'' . $url . '\')" />';

	// add the element to the form
	return addElement($temp, $form);
}

// returns an add section button
function appendAddSectionButton(&$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<input type="button" value="Add New Section" onclick="addSection()" />';

	// add the element to the form
	return addElement($temp, $form);
}

// returns an add section button
function appendRemoveSectionButton($section, &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<input type="button" value="Remove Section" class="removebutton" onclick="removeSection(' . $section . ')" />';

	// add the element to the form
	return addElement($temp, $form);
}

// returns an option list created from an array with the exception of entries equal to $except
function appendOptionsFromArray($array, $except = '', $name = '', &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '';
	
	// loop through the array, creating options
	foreach($array as $entry){
		if($entry != $except)
			$temp .= '<input type="radio" name="' . $name .  '" value="' . array_search($entry, $array) . '" />' . $entry . '<br>';
	}

	// add the element to the form
	return addElement($temp, $form);
}

// adds an h1 heading to the given form and returns the heading HTML as a string
function addTitleHeading($text, &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<h1>' . $text . '</h1>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds an h2 heading to the given form and returns the heading HTML as a string
function addHeading($text, &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<h2>' . $text . '</h2>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds an h3 heading to the given form and returns the heading HTML as a string
function addSubheading($text, &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<h3>' . $text . '</h3>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds an unordered list container to the given form and returns the heading HTML as a string
function addUnorderedList($text, &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<ul>' . $text . '</ul>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds a list element to the given form and returns the heading HTML as a string
function addListElement($text, &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<li>' . $text . '</li>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds a password field with the given name
function addPasswordField($name, $caption = '', $type = 'password', $class = '', &$form = NULL){
	// password fields are always incomplete when first loaded
	$class .= ' incomplete';
	
	// set a temp variable to hold the string to append/return
	$temp = $caption . '<input type="' . $type . '" name="' . $name . '" onfocus="startValidateField(\'' . $name . '\', \'\')"'
		. ' onblur="stopValidateField(); fieldIsNonEmpty(\'' . $name . '\', \'\');" class="' . $class . '" />';	
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds a text field with the given name
function addTextField($name, $caption = '', $defaultText = '', $section = '', $type = 'text', $class = '', &$form = NULL){
	// if there is default text, then it's not incomplete
	$class .= (strlen($defaultText) > 0) ? ' complete' : ' incomplete';
	
	// set a temp variable to hold the string to append/return
	$temp = $caption . '<input type="' . $type . '" name="' . $name . '" value="' . $defaultText . '" onfocus="startValidateField(\'' . $name . '\', \''
		. $section . '\')" onblur="stopValidateField(); fieldIsNonEmpty(\'' . $name . '\', \'' . $section . '\');" class="' . $class . '" />';	
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds a text field with the given name and a default value it returns to if empty
function addDefaultValueTextField($name, $caption = '', $defaultText = '', $section = '', $type = 'text', &$form = NULL){
	// if there is default text, then it's not incomplete
	$class = (strlen($defaultText) > 0) ? 'complete' : 'incomplete';
	
	// set a temp variable to hold the string to append/return
	$temp = $caption . '<input type="' . $type . '" name="' . $name . '" value="' . $defaultText . '" onfocus="defVal(\'' . $name . '\', \'' . $section . '\');'
		. ' startValidateField(\'' . $name . '\', \'' . $section . '\');" onblur="defVal(\'' . $name . '\', \'' . $defaultText . '\'); stopValidateField(); fieldIsNonEmpty(\'' . $name . '\', \'' . $section . '\');" class="' . $class . '" />';	
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds a date text field with the given name
function addDateField($name, $caption = '', $defaultText = '', $section = '', $type = 'text', &$form = NULL){
	// if there is default text, then it's not incomplete
	$class = (strlen($defaultText) > 0) ? 'complete date' : 'incomplete date';
	
	// set a temp variable to hold the string to append/return
	$temp = $caption . '<input type="' . $type . '" name="' . $name . '" value="' . $defaultText . '" onfocus="startValidateField(\'' . $name . '\', \''
		. $section . '\')" onblur="stopValidateField(); fieldIsNonEmpty(\'' . $name . '\', \'' . $section . '\');" class="' . $class . '" />';	
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds a text field with the given name
function addTextArea($name, $caption = '', $defaultText = '', $section = '', $rows = 3, $columns = 50, &$form = NULL){
	// if there is default text, then it's not incomplete
	$class = (strlen($defaultText) > 0) ? 'complete' : 'incomplete';
	
	// set a temp variable to hold the string to append/return
	$temp = $caption . '<br><textarea name="' . $name . '" rows="' . $rows . '" cols="' . $columns . '" onfocus="startValidateField(\'' . $name
		. '\', \'' . $section . '\')" onblur="stopValidateField(); fieldIsNonEmpty(\'' . $name . '\', \'' . $section . '\');" class="' . $class . '">' . $defaultText . '</textarea>';	
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds a hyperlink to the given url with the given caption to the given form string and returns the link HTML as a string
function appendLink($caption, $url, &$form = NULL){
	// set a temp variable to hold the link to append/return
	$temp = '<a href="' . $url . '" class="pagelink">' . $caption . '</a>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds an open paragraph tag with the given text following it to the given form string and returns the text with paragraph tag as a string
function openParagraph($text = '', &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<p>' . $text;
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds a closing paragraph tag with the given text preceding it to the given form string and returns the text with paragraph tag as a string
function closeParagraph($text = '', &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = $text . '</p>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// adds a full paragraph tag with the given text inside it to the given form string and returns the paragraph HTML as a string
function addParagraph($text, &$form = NULL){
	// use the open and close functions to create and add the paragraph
	return openParagraph($text, $form) . closeParagraph('', $form);
}

/* functions for footer */

// appends a footer cell with the given contents
function appendDiv($contents, $class = '', $id = NULL, &$form = NULL){
	// temp variable for id setting
	$idfield = '';
	
	// if id is not null, use it
	if($id !== NULL){
		$idfield = 'id="' . $id . '"';
	}
	
	// set a temp variable to hold the string to append/return
	$temp = '<div ' . $idfield . ' class="' . $class . '">' . $contents . '</div>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// appends an icon of the image source specified with the given caption, and linking to the given url
function appendIcon($caption, $url, $image, $target='', &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '<a href="' . $url . '" target="' . $target . '"><img src="' . $image . '" /></a><p><a href="' . $url . '">' . $caption . '</p></a>';
	
	// add the element to the form
	return addElement($temp, $form);
}

// appends a help icon with the given caption, and linking to the given url
function appendHelpIcon($caption, $url = 'help.php', &$form = NULL){	
	return appendIcon($caption, $url, 'images/help.png', $form);
}

// appends a new form icon with the given caption, and linking to the given url
function appendNewIcon($caption, $url = 'new_resume.php', &$form = NULL){	
	return appendIcon($caption, $url, 'images/newform.png', $form);
}

// appends an index icon with the given caption, and linking to the given url
function appendIndexIcon($caption, $url = 'index.php', &$form = NULL){	
	return appendIcon($caption, $url, 'images/index.png', $form);
}

// appends a logout icon with the given caption, and linking to the given url
function appendLogoutIcon($caption, $url = 'logout.php', &$form = NULL){	
	return appendIcon($caption, $url, 'images/logout.png', $form);
}

// appends a login icon with the given caption, and linking to the given url
function appendLoginIcon($caption, $url = 'login.php', &$form = NULL){
	// append the referer url if we were sent to the login screen by another page	
	$urlquery = array('referer' => getSessionVar('http_referer'));
	
	// append the referer as a URL parameter
	$url .= '?' . http_build_query($urlquery);
	
	// return the icon
	return appendIcon($caption, $url, 'images/arrow_green.png', $form);
}

// appends a register icon with the given caption, and linking to the given url
function appendRegisterIcon($caption, $url = 'register.php', &$form = NULL){	
	return appendIcon($caption, $url, 'images/form_icon.png', $form);
}

// appends a save icon with the given caption, and linking to the given url
function appendSaveIcon($caption, $url = 'save_load.php', &$form = NULL){	
	return appendIcon($caption, $url, 'images/save_icon.png', '_blank', $form);
}

// appends an admin icon with the given caption, and linking to the given url
function appendAdminIcon($caption, $url = 'admin.php', &$form = NULL){	
	return appendIcon($caption, $url, 'images/admin.png', $form);
}

// appends a switch to user view icon with the given caption, and linking to the given url
function appendUserIcon($caption, $url = 'user.php', &$form = NULL){	
	return appendIcon($caption, $url, 'images/user.png', $form);
}

// appends an icon of a form with the given caption, and linking to the given url. If $complete is true, then it shows a form icon with a green check mark
function appendFormIcon($complete, $caption, $url, &$form = NULL){
	// set a temp variable to hold the string to append/return
	if($complete){
		return appendIcon($caption, $url, 'images/form_icon_complete.png', $form);
	} else {
		return appendIcon($caption, $url, 'images/form_icon.png', $form);
	}
}

// appends an icon of a resume with the first caption (if resume is ready to view), or the second caption (if it is not ready). It links to the resume page if the resume is ready to view
function appendResumeIcon($complete, $captionReady, $captionUnready, $url = 'resume.php', &$form = NULL){
	// set a temp variable to hold the string to append/return
	if($complete){
		return appendIcon($captionReady, $url, 'images/resume.png', $form);
	} else {
		return appendIcon($captionUnready, $url, 'images/resume.png', $form);
	}
}

// returns true if a username or password have been posted
function registrationAttempted(){
	return isset($_POST['username']) || isset($_POST['password']);
}

/* form validation and session variable saving */
function validate(){
	// check if the icons should be set
	validateOnLoad();
	
	if(isset($_POST['sourceurl'])){
		// if form data came from new_resume.php, validate it (only used when creating a new resume)
		if($_POST['sourceurl'] == 'new_resume.php'){
			return validateNewResume();
		}
		
		// if form data came from login.php, validate it and log user in if applicable
		if($_POST['sourceurl'] == 'login.php'){
			return validateLogin();
		}
		
		// if form data came from register.php, validate it and add a new user to database if applicable
		if($_POST['sourceurl'] == 'register.php'){
			return validateUser();
		}
		
		// if form data came from contact_information.php, validate it and add it to session information if applicable
		if($_POST['sourceurl'] == 'contact_information.php'){
			return validateCI();
		}
		
		// if form data came from position_sought.php, validate it and add it to session information if applicable
		if($_POST['sourceurl'] == 'position_sought.php'){
			return validatePS();
		}
		
		// if form data came from employment_history.php, validate it and add it to session information if applicable
		if($_POST['sourceurl'] == 'employment_history.php'){
			return validateEH();
		}
		// if form data came from save_load.php, validate it (only used when previewing a resume)
		if($_POST['sourceurl'] == 'save_load.php'){
			return validatePreview();
		}
	}
}

// validates form information submitted from new_resume.php
function validateNewResume(){
	// get the current session
	$resumeInformation =& getSession();
	
	// check that user name exists
	if(isset($_POST['resumename'])){
		// set the session to have the resume name
		$resumeInformation['resumename'] = $_POST['resumename'];
		
		return true;
	}
	
	// if we made it here, the validation failed. return false
	return false;
}

// validates form information submitted from login.php
function validateLogin(){	
	// get the current session
	$resumeInformation =& getSession();
	
	// set a variable to track if all fields have been filled
	$allvalid = true;
	
	// check that user name exists
	if(isset($_POST['username'])){
		// validation tests for username
	} else {
		$allvalid = false;
	}
	
	// check that password exists
	if(isset($_POST['password'])){
		// validation tests for password
	} else {
		$allvalid = false;
	}
	
	// if variables exist and are valid, log user in
	if($allvalid){
		
		// add the new user to the database and save its user ID
		$userid = login($_POST['username'], $_POST['password']);
		
		// if the login failed, return false
		if ($userid == 0)
			return false;
		
		// set user ID
		$_SESSION['userID'] = $userid;
	}
	
	return $allvalid;
}

// validates form information submitted from register.php
function validateUser(){
	// get the current session
	$resumeInformation =& getSession();
	
	// set a variable to track if all fields have been filled
	$allvalid = true;
	
	// check that user name exists
	if(isset($_POST['username']) && (strlen(trim($_POST['username'])) >= 8) && (trim($_POST['username']) == $_POST['username'])){
		// validation tests for user name
	} else {
		$allvalid = false;
	}
	
	// check that password exists
	if(isset($_POST['password']) && (strlen(trim($_POST['password'])) >= 8) && (trim($_POST['password']) == $_POST['password'])){
		// validation tests for password
	} else {
		$allvalid = false;
	}
	
	// check that real name exists
	if(isset($_POST['realname'])){
		// validation tests for real name
	} else {
		$allvalid = false;
	}
	
	// if variables exist and are valid, add new user to the database
	if($allvalid){
		
		// add the new user to the database and save its user ID
		$userid = addUser($_POST['username'], $_POST['password'], $_POST['realname']);
		
		// if the insert failed, return false
		if ($userid == 0)
			return false;
		
		// set the session to have the correct user ID number
		$_SESSION['userID'] = $userid;
	}
	
	return $allvalid;
}

// validates form information submitted from contact_information.php
function validateCI(){
	// get the current session
	$resumeInformation =& getSession();
	
	// set a variable to track if all fields have been filled
	$allvalid = true;
	
	// check that name exists
	if(isset($_POST['fullname'])){
		// validation tests for name
	} else {
		$allvalid = false;
	}
	
	// check that phone number exists
	if(isset($_POST['phonenumber'])){
		// validation tests for phone number
	} else {
		$allvalid = false;
	}
	
	// check that address exists
	if(isset($_POST['address'])){
		// validation tests for address
	} else {
		$allvalid = false;
	}
	
	// if variables exist and are valid, so add them to the session and set this page to complete
	if($allvalid){
		// add the variables to the session
		$resumeInformation['fullname'] = $_POST['fullname'];
		$resumeInformation['phonenumber'] = $_POST['phonenumber'];
		$resumeInformation['address'] = $_POST['address'];
		
		// set this page to complete
		$resumeInformation['contactInfoComplete'] = true;
	} else {
		// set this page to incomplete
		$resumeInformation['contactInfoComplete'] = false;
	}
}

// validates form information submitted from position_sought.php
function validatePS(){
	// get the current session
	$resumeInformation =& getSession();
	
	// set a variable to track if all fields have been filled
	$allvalid = true;
	
	// check that position description exists
	if(isset($_POST['description'])){
		// validation tests for position description
	} else {
		$allvalid = false;
	}
	
	// if variables exist and are valid, so add them to the session and set this page to complete
	if($allvalid){
		// add the variables to the session
		$resumeInformation['description'] = $_POST['description'];
		
		// set this page to complete
		$resumeInformation['positionSoughtComplete'] = true;
	} else {
		// set this page to incomplete
		$resumeInformation['positionSoughtComplete'] = false;
	}
}

// validates form information submitted from employment_history.php
function validateEH(){
	
	// get the current session
	$resumeInformation =& getSession();
	
	// set a variable to track if all fields have been filled
	$allvalid = true;
	
	// check that job title exists
	if(isset($_POST['jobtitle'])){
		// validation tests for job title
	} else {
		$allvalid = false;
	}
	
	// check that start date exists
	if(isset($_POST['startdate'])){
		// check that date is in YYYY-MM-DD format
		for($i = 0; $i < count($_POST['startdate']); $i++){
			$startdate = $_POST['startdate'];
			$allvalid = $allvalid && validateDate($startdate[$i]);
		}
	} else {
		$allvalid = false;
	}
	
	// check that end date exists
	if(isset($_POST['enddate'])){
		// check that date is in YYYY-MM-DD format
		for($i = 0; $i < count($_POST['enddate']); $i++){
			$enddate = $_POST['enddate'];
			$allvalid = $allvalid && validateDate($enddate[$i]);
		}
	} else {
		$allvalid = false;
	}
	
	// check that position description exists
	if(isset($_POST['positiondescription'])){
		// validation tests for position description
	} else {
		$allvalid = false;
	}
	
	// if variables exist and are valid, so add them to the session and set this page to complete
	if($allvalid){
		// add the variables to the session
		$resumeInformation['jobtitle'] = $_POST['jobtitle'];
		$resumeInformation['startdate'] = $_POST['startdate'];
		$resumeInformation['enddate'] = $_POST['enddate'];
		$resumeInformation['positiondescription'] = $_POST['positiondescription'];
		
		// set this page to complete
		$resumeInformation['employmentHistoryComplete'] = true;
	} else {
		// set this page to incomplete
		$resumeInformation['employmentHistoryComplete'] = false;
	}
}

// validates form information submitted to resume.php from save_load.php
function validatePreview(){	
	// check that a resume id has been selected
	if(isset($_POST['resumeid']) && $_POST['resumeid'] > 0){
		return true;
	} else {
		// no resume id was selected, go back to save/load page
		header('Location: save_load.php');
	}
}

// checks which icons should be checked following a form load
function validateOnLoad(){
	// get the current session
	$resumeInformation =& getSession();
	
	// if variables exist and are valid mark their completion variables
	
	// checking contact info
	if(nonempty('fullname') && nonempty('phonenumber') && nonempty('address')){
		// set this page to complete
		$resumeInformation['contactInfoComplete'] = true;
	} else {
		// set this page to incomplete
		$resumeInformation['contactInfoComplete'] = false;
	}
	
	// checking position sought
	if(nonempty('description')){
		// set this page to complete
		$resumeInformation['positionSoughtComplete'] = true;
	} else {
		// set this page to incomplete
		$resumeInformation['positionSoughtComplete'] = false;
	}
	
	// checking employment history
	$allvalid = true;
	for($i = 0; $i < getResumeArrayLength('jobtitle'); $i++){
		$startdate = isset($_SESSION['startdate']) ? $_SESSION['startdate'] : array();
		$enddate = isset($_SESSION['enddate']) ? $_SESSION['enddate'] : array();
		$allvalid = $allvalid && (nonemptyArray('jobtitle', $i) && nonemptyArray('startdate', $i, true) && nonemptyArray('enddate', $i, true) && nonemptyArray('positiondescription', $i)); 
	}
	
	// there should be at least one entry in the employment history page before checking it off
	$allvalid = $allvalid && (getResumeArrayLength('jobtitle') > 0);
	
	// checking employment history
	if($allvalid){
		// set this page to complete
		$resumeInformation['employmentHistoryComplete'] = true;
	} else {
		// set this page to incomplete
		$resumeInformation['employmentHistoryComplete'] = false;
	}
}

// returns true if a variable is set and is nonempty
function nonempty($name){
	// get the current session
	$resumeInformation =& getSession();
	
	return isset($resumeInformation[$name]) && strlen(trim($resumeInformation[$name])) > 0;
}

// returns true if a variable is set and is nonempty
function nonemptyArray($name, $index, $isDate = false){
	// get the current session
	$resumeInformation =& getSession();
	
	return isset($resumeInformation[$name][$index]) && strlen(trim($resumeInformation[$name][$index])) > 0 && !($isDate && !validateDate($resumeInformation[$name][$index]));
}

// checks to see if there is a URL parameter set to display a saved message. returns 1 on success, -1 on failure, and 0 if there is no saved status
// -- Do not use for actual validation, this is for display purposes only --
function isSaved(){
	// check to see if there is a saved parameter, and what its contents are
	if(isset($_GET['saved'])){
		if($_GET['saved'] == 'yes'){
			return 1;
		} elseif($_GET['saved'] == 'no'){
			return -1;
		}
	}
	
	// if we made it here, there was no save status to report on
	return 0;
}

// checks to see if there is a URL parameter set to display a loaded message
// -- Do not use for actual validation, this is for display purposes only --
function isLoaded(){
	// check to see if there is a saved parameter, and what its contents are
	if(isset($_GET['loaded'])){
		if($_GET['loaded'] == 'yes'){
			return 1;
		} elseif($_GET['loaded'] == 'no'){
			return -1;
		}
	}
	
	// if we made it here, there was no save status to report on
	return 0;
}

// checks to see if there is a URL parameter set to display a deleted message
// -- Do not use for actual validation, this is for display purposes only --
function isDeleted(){
	// check to see if there is a saved parameter, and what its contents are
	if(isset($_GET['deleted'])){
		if($_GET['deleted'] == 'yes'){
			return 1;
		} elseif($_GET['deleted'] == 'no'){
			return -1;
		}
	}
	
	// if we made it here, there was no save status to report on
	return 0;
}

// returns a status message if something was saved/loaded/deleted successfully. If not, returns the passed-in string.
function getSaveLoadDeleteStatus($default){
	
	// check on saved status
	if(isSaved() == 1)
		return 'Saved successfully.';
	if(isSaved() == -1)
		return 'Save failed.';
	
	// check on loaded status
	if(isLoaded() == 1)
		return 'Loaded successfully.';
	if(isLoaded() == -1)
		return 'Load failed.';
	
	// check on deleted status
	if(isDeleted() == 1)
		return 'Deleted successfully.';
	if(isDeleted() == -1)
		return 'Delete failed.';
	
	// if we made it here, just return the default message
	return $default;
} 

// checks if $field matches a YYYY-MM-DD date format
function validateDate($field){
	// return true if the field is in YYYY-MM-DD format
	if(preg_match('/\d\d\d\d-\d\d?-\d\d?/', $field))
		return true;
	return false;
}

// returns an option list created from an array with the exception of entries equal to $except
function addUsersTable($array, $self = '', $name = '', &$form = NULL){
	// set a temp variable to hold the string to append/return
	$temp = '';

	// get the sub-arrays out of the main array
	$userids = $array[0];
	$usernames = $array[1];
	$realnames = $array[2];
	$roles = $array[3];
	
	// default to displaying the admin section, since results are returned ordered by role
	$adminsection = true;
	$temp .= addSubheading('Administrators');
	
	$temp .= '<table><tr><td>User ID</td><td>Username</td><td>Real Name</td><td>Role</td><td></td><td></td></tr>';
	
	// loop through the array, creating user rows
	for($i = 0; $i < count($userids); $i++){
		// if displayed users are no longer admins, start a new section for clients
		if($adminsection && $roles[$i] != 'admin'){
			$adminsection = false;
			$temp .= '</table>';
			$temp .= addSubheading('Users');
			$temp .= '<table><tr><td>User ID</td><td>Username</td><td>Real Name</td><td>Role</td><td></td><td></td></tr>';
		}
		
		// display different fields for yourself, so you can't accidentally un-admin yourself
		if($userids[$i] != $self){
			$temp .= '<tr><td>' . $userids[$i] . '</td><td>' . $usernames[$i] . '</td><td>' . $realnames[$i] . '</td><td>' . addAdminClientRadio($roles[$i], $userids[$i])
			 	. '</td><td>' . addAJAXButton('updateRole(' . $userids[$i] . ')', 'Update User Role')
				. '</td><td>' . addAJAXButton('deleteUser(' . $userids[$i] . ')', 'Delete User') . '</td></tr>';
		} else {
			$temp .= '<tr><td>' . $userids[$i] . '</td><td>' . $usernames[$i] . '</td><td>' . $realnames[$i] . '</td><td>' . $roles[$i] . '</td></tr>';
		}
	}
	
	$temp .= '</table>';

	// add the element to the form
	return addElement($temp, $form);
}

// adds a button that runs the passed-in string as a javascript function
function addAJAXButton($funct = '', $caption = ''){
	return '<button onclick="' . $funct . '">' . $caption . '</button>';
}

// adds two radio buttons representing admin and client rights as an HTML string
function addAdminClientRadio($checked, $index){
	// begin admin radio button
	$temp = '<input type="radio" name="role' . $index . '"';
	
	// if user is an admin, have that selection checked
	if($checked == 'admin'){
		$temp .= ' checked="true"';
	}
	
	$temp .= ' value="admin"/>Admin';
	
	// begin client radio button
	$temp .= '<input type="radio" name="role' . $index . '"';
	
	// if user is a client, have that selection checked
	if($checked != 'admin'){
		$temp .= ' checked="true"';
	}
	
	$temp .= ' value="client"/>Client';
	
	return $temp;
}