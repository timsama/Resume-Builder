<?php

// include our custom php functions
require_once 'functions.php';
require_once 'session.php';
require_once 'referer.php';

// suppress the ResumeBuilder title from showing up
$suppresstitle = true;

// get a reference to the resume information
$resumeInformation =& getSession();

// validate that a resume was selected
validate();

// set up array of database variables, or leave blank and use session variables
if(isset($_GET['name']) && isset($_GET['username'])){
	$resume = previewResume($_GET['name'], $_GET['username']);
} elseif(isset($_POST['resumeid'])) {
	$resume = loadArrayFromDatabase($_POST['resumeid']);
} else {
	$resume = '';
}

$formface = '';

// add the address and phone number across the top
$formface .= appendDiv(getResumeVar('address', $resume) . ' &mdash; ' . getResumeVar('phonenumber', $resume), 'topborder');

// add the name as main header
$formface .= addTitleHeading(getResumeVar('fullname', $resume));

// add position desired as header and position desired text as paragraph to a body string that will get added into a div
$body = addHeading('Position Desired') . addParagraph(getResumeVar('description', $resume));

// add employment history heading to body string
$body .= addHeading('Employment History');

// add a section of employment history for each one that is present
for($i = 0; $i < getResumeArrayLength('jobtitle', $resume); $i++){
	$body .= addSubheading(getResumeArrayVar('jobtitle', $i, $resume));
	$body .= openParagraph(getResumeArrayVar('startdate', $i, $resume)) . ' &mdash; ' . closeParagraph(getResumeArrayVar('enddate', $i, $resume));
	$body .= addUnorderedList(addListElement(getResumeArrayVar('positiondescription', $i, $resume)));
}

// add the position desired and employment history sections into one div
$formface .= appendDiv($body, 'description');

require 'template.php';