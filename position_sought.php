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
$formface .= addHeading('Position Sought');

// start the HTML form
$formface .= startForm('position_sought.php');

// add hidden field to store this page as the page we came from
$formface .= addHiddenField('sourceurl', 'position_sought.php');

// add position description field
$formface .= addParagraph(addTextArea('description', 'Describe Sought Position: ', getResumeVar('description'), '', 5, 100));

// add submit button
$formface .= appendSubmitButton();

// end the HTML form
$formface .= closeForm();

require 'template.php';