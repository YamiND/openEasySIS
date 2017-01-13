<?php 

include("../includes/customizations.php");

function displaySite($title = NULL, $functionFile = NULL, $functionName = NULL, $pageHeader = NULL)
{

    echo '
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <title>' . aliasOpenEasySIS . ' - ' . $title . '</title>
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
        		include("$functionFile");
    echo '
                <div id="page-wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">' . $pageHeader . '</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
            ';
			
	call_user_func("$functionName", $mysqli);

    echo '
                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

        </body>

        </html>
    ';
}

?>