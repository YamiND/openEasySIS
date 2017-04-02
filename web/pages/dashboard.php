<?php

// Our constants
include("../includes/customizations.php");

// Our admin functions
include('../includes/userFunctions/viewAnnouncements.php'); 
include('../includes/adminFunctions/viewTotalAccounts.php'); 
include('../includes/adminFunctions/viewTotalClasses.php'); 

echo '
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <title>' . aliasOpenEasySIS . ' - Dashboard</title>
            <!-- Header Information, CSS, and JS -->
            ';

            include("../includes/header.php");
    echo '
        </head>

        <body>

            <div id="wrapper">

        	<!-- Navigation Menu -->
        ';
                include('../includes/navPanel.php'); 
    echo '
                <div id="page-wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Dashboard</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
            ';
		viewAnnouncements($mysqli);

		if (isStudent($mysqli))
		{
/*
			This info can be viewed in the User's profile
			echo "<h4>Current School Year GPA: " . number_format((float) getCurrentSchoolYearGPA($_SESSION['userID'], $mysqli), 2, '.', '') . "</h4>";
			echo "<h4>Cumulative GPA: " . number_format((float) getTotalGPA($_SESSION['userID'], $mysqli), 2, '.', '') . "</h4>";
			echo "<h4>Graduation Year: " . getStudentGraduationYear($_SESSION['userID'], $mysqli) . "</h4>";*/

			getStudentClassSchedule($_SESSION['userID'], $mysqli);
		}
    	// View our announcements	

		//Add our multi code here	
		if (isAdmin($mysqli))
		{
		    viewTotalUsers($mysqli, "Administrators", "isAdmin");
    		viewTotalUsers($mysqli, "Students", "isStudent");
    		viewTotalUsers($mysqli, "Teachers", "isTeacher");
    		viewTotalUsers($mysqli, "Parents", "isParent");

    		viewTotalClasses($mysqli);
		}
		
		if (isTeacher($mysqli))
		{
			include("../includes/teacherFunctions/viewTeacherDashboard.php");	
		}
		
		if (isSchoolAdmin($mysqli))
		{
			include("../includes/schoolAdminFunctions/viewSchoolAdminDashboard.php");	
		}
		
		if (isStudent($mysqli))
		{
			include("../includes/studentFunctions/viewStudentDashboard.php");	
		}
		
		if (isParent($mysqli))
		{
			include("../includes/parentFunctions/viewParentDashboard.php");	
		}


    echo '
                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

        </body>

        </html>
    ';
?>
