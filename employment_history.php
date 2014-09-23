<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';
require_once 'referer.php';

// get a reference to the resume information
$resumeInformation =& getSession();

// validate any inputs that were posted to this page
validate();

// initialize form face
$formface = '';

// add page heading
$formface .= addHeading('Employment History');

// start the HTML form
$formface .= startForm('employment_history.php');

// add hidden field to store this page as the page we came from
$formface .= addHiddenField('sourceurl', 'employment_history.php');

// prepare a container for all controls
$container = '';

// get the length of any session variable arrays
$array_length = 0;

// only get a nonzero array length if there are an equal number of all fields
if(getResumeArrayLength('jobtitle') == getResumeArrayLength('startdate') && getResumeArrayLength('startdate') == getResumeArrayLength('enddate')
	&& getResumeArrayLength('enddate') == getResumeArrayLength('positiondescription')){
	
	$array_length = getResumeArrayLength('jobtitle');
}

// loop for as many times as necessary to cover all session array variables
for($i = 0; ($i == 0) || ($i < $array_length); $i++){
	// set/reset controls section
	$controls = '';
	
	// add job title field
	$controls .= addParagraph(addTextField('jobtitle[]', 'Title: ', getResumeArrayVar('jobtitle', $i), $i));
	
	// add starting and ending date fields
	$controls .= addParagraph(addDateField('startdate[]', 'Start Date (YYYY-MM-DD): ', getResumeArrayVar('startdate', $i), $i) . ' '
		. addDateField('enddate[]', 'End Date (YYYY-MM-DD): ', getResumeArrayVar('enddate', $i), $i) . appendRemoveSectionButton($i));
	
	// add position description field
	$controls .= addParagraph(addTextArea('positiondescription[]', 'Position Description: ', getResumeArrayVar('positiondescription', $i), $i, 3, 100));
	
	// add the controls in their own div
	$container .= appendDiv($controls, '', 'section' . $i);
}

//  wrapped in a container div
$formface .= appendDiv($container, 'default', 'employmenthistory');

// add submit button
$formface .= appendSubmitButton();

// add add section button
$formface .= appendAddSectionButton();

// end the HTML form
$formface .= closeForm();

require 'template.php';