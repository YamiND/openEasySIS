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
    	// View our announcements	
		viewAnnouncements($mysqli);

		//Add our multi code here	
		if (isAdmin($mysqli))
		{
		    viewTotalUsers($mysqli, "Administrators", "adminProfile");
    		viewTotalUsers($mysqli, "Students", "studentProfile");
    		viewTotalUsers($mysqli, "Teachers", "teacherProfile");
    		viewTotalUsers($mysqli, "Parents", "parentProfile");

    		viewTotalClasses($mysqli);
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
