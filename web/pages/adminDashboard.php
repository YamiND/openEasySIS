<?php

// Most pages (if not all), can use this template file
include("../includes/pageTemplate.php");

// Function call goes like this:
// Title (<title>), functionFile (should be in ../includes/*), functionName, pageHeader

// All functionNames should be checkPermissions, unless it is a user function that can be accessed by all. In functionFile, but NOT HERE
// if you do something like viewForm($mysqli) in this file, IT WILL NOT WORK

echo displaySite("Admin Dashboard", "../includes/adminFunctions/viewAdminDashboard.php", "checkPermissions", "Admin Dashboard");

?>
