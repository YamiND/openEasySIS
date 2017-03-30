<?php

// Most pages (if not all), can use this template file
include("../includes/pageTemplate.php");

// Function call goes like this:
// Title (<title>), functionFile (should be in ../includes/*), functionName, pageHeader

// All functionNames need to have ($mysqli) in functionFile, but NOT HERE
// if you do something like viewForm($mysqli) in this file, IT WILL NOT WORK

echo displaySite("Assign Teacher to Class", "../includes/adminFunctions/viewAssignTeacherClassForm.php", "checkPermissions", "Assign Teacher to Class");

?>
