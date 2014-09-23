<?php

// include our custom php functions
require_once 'functions.php';

// clear session variables and then start fresh
session_start();
session_unset();
session_destroy();

// redirect to non-secure connection
redirectToHTTP();

// go to the contact information page
header('Location: contact_information.php');