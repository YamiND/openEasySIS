<?php

include('../includes/userFunctions/viewAnnouncements.php'); 
include('../includes/adminFunctions/viewTotalAccounts.php'); 
include('../includes/adminFunctions/viewTotalClasses.php'); 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewAdminDashboard($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewAdminDashboard($mysqli)
{
    // Provide 
    viewAnnouncements($mysqli);

    viewTotalUsers($mysqli, "Administrators", "adminProfile");
    viewTotalUsers($mysqli, "Students", "studentProfile");
    viewTotalUsers($mysqli, "Teachers", "teacherProfile");
    viewTotalUsers($mysqli, "Parents", "parentProfile");

    viewTotalClasses($mysqli);
}

?>
