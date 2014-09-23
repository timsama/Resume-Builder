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
$formface .= addHeading('Contact Information');

// start the HTML form
$formface .= startForm('contact_information.php');

// add hidden field to store this page as the page we came from
$formface .= addHiddenField('sourceurl', 'contact_information.php');

// add user name and phone number fields
$formface .= addParagraph(addTextField('fullname', 'Full Name: ', getResumeVar('fullname')) . ' ' . addTextField('phonenumber', 'Phone Number: ', getResumeVar('phonenumber')));

// add address field
$formface .= addParagraph(addTextArea('address', 'Address: ', getResumeVar('address'), '', 5, 100));

// add submit button
$formface .= appendSubmitButton();

// end the HTML form
$formface .= closeForm();

require 'template.php';