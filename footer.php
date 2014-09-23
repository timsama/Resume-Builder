<?php

// include our custom php functions
require_once 'functions.php';

// get a reference to the resume information
$resumeInformation =& getSession();

// initialize the footer
$footer = '';

// check if contact information, position sought, and employment history have been entered
$contactInfoComplete = formIsComplete('contactInfoComplete');
$positionSoughtComplete = formIsComplete('positionSoughtComplete');
$employmentHistoryComplete = formIsComplete('employmentHistoryComplete');

// display index, contact information, position sought, and employment history buttons
$footer .= appendDiv(appendIndexIcon('Index'), 'footercell');
$footer .= appendDiv(appendFormIcon($contactInfoComplete, 'Contact<br>Information', 'contact_information.php'), 'footercell');
$footer .= appendDiv(appendFormIcon($positionSoughtComplete, 'Position<br>Sought', 'position_sought.php'), 'footercell');
$footer .= appendDiv(appendFormIcon($employmentHistoryComplete, 'Employment<br>History', 'employment_history.php'), 'footercell');

// check if everything has been entered
$everythingEntered = $contactInfoComplete && $positionSoughtComplete && $employmentHistoryComplete;

// display resume button
$footer .= appendDiv(appendResumeIcon($everythingEntered, 'View<br>Resume', 'View Incomplete<br>Resume'), 'footercell');

// display save, logout, new form, and help buttons
$footer .= appendDiv(appendNewIcon('Start<br>New Resume'), 'footercellright');
$footer .= appendDiv(appendHelpIcon('Help<br>'), 'footercellright');

// add user name if logged in
$footer .= (strlen(trim(getSessionVar('realname'))) > 0) ? '<b>Current User: </b>' . getSessionVar('realname') : '';

// display admin page icon if user is an admin
if(checkRole('admin')){
	$footer .= appendDiv(appendAdminIcon('Account<br>Administration'), 'footercellright');
	$footer .= appendDiv(appendUserIcon('Switch to<br>Client View'), 'footercellright');
}

// display logout and save/load if user is logged in
if(checkRole('client')){
	$footer .= appendDiv(appendSaveIcon('Save/Load<br>'), 'footercellright');
	$footer .= appendDiv(appendLogoutIcon('Log Out<br>'), 'footercellright');
} else {
	$footer .= appendDiv(appendRegisterIcon('Create an Account'), 'footercellright');
	$footer .= appendDiv(appendLoginIcon('Log In'), 'footercellright');
}


// output the footer
echo $footer;

// DEBUG: show session info
// print_r($_SESSION);