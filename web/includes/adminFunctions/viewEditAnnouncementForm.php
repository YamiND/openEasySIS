<?php

if (isset($_POST['announcementID']))
{
	// After the user selects an announcement, set it as a $_SESSION variable
    $_SESSION['announcementID'] = $_POST['announcementID'];
}

if (isset($_POST['changeAnnouncement']))
{
	// User wants to change announcements, unset the announcementID
    unset($_SESSION['announcementID']);
}

if (isset($_SESSION['success']))
{
    unset($_SESSION['announcementID']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
    {
        viewEditAnnouncementForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

// We have to set the correct Date for the date() function otherwise it uses UTC
date_default_timezone_set('America/New_York');

function viewEditAnnouncementForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
       	';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Edit Announcement");
	echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#editAnnoucement" data-toggle="tab">Edit Announcement</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="editAnnouncement">
                                <br>
                  	';

                                	if (!isset($_SESSION['announcementID']))
                                	{
                                		// Call form to set announcement ID
                                		getAnnouncementsForm($mysqli);
                                	}
                                	else
                                	{
                                		// We have announcementID, bring up form to modify announcement
                                		modifyAnnouncementForm($_SESSION['announcementID'], $mysqli);
                                        echo "<br>";
                                		// Allows us to change Announcements
                                		changeAnnouncementButton();
                                	}
				echo '
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
	';

}

function changeAnnouncementButton()
{
    // Form to allow user to change announcement selected
    generateFormStart("", "post");  
        generateFormHiddenInput("changeAnnouncement", "changeAnnouncement");
        generateFormButton("changeAnnouncementButton", "Choose Another Announcement");
    generateFormEnd();
}

function modifyAnnouncementForm($announcementID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT announcementName, announcementDescription, announcementPostDate, announcementEndDate FROM announcements WHERE announcementID = ?"))
    {   
    	// Query database for announcement details to be modified
    	$stmt->bind_param('i', $announcementID);

    	$stmt->execute();
        $stmt->bind_result($announcementName, $announcementDescription, $announcementPostDate, $announcementEndDate);
        $stmt->store_result();
        $stmt->fetch();

        generateFormStart("../includes/adminFunctions/editAnnouncement", "post"); 
            generateFormHiddenInput("announcementID", $announcementID);
            generateFormInputDiv("Announcement Name", "text", "announcementName", $announcementName);
            generateFormInputDiv("Announcement Post Date", "date", "announcementPostDate", $announcementPostDate);
            generateFormInputDiv("Announcement End Date", "date", "announcementEndDate", $announcementEndDate);
            generateFormTextAreaDiv("Announcement Description", "announcementDescription", $rows = "5", $announcementDescription);
            generateFormButton("editAnnouncementButton", "Edit Announcement");
        generateFormEnd();
    }  
}

function getAnnouncementsForm($mysqli)
{								
	if ($stmt = $mysqli->prepare("SELECT announcementID, announcementPostDate, announcementName FROM announcements WHERE announcementEndDate >= curdate()"))
    {   
    	// Query and Display all available announcements
    	$stmt->execute();
        $stmt->bind_result($announcementID, $announcementPostDate, $announcementName);
        $stmt->store_result();

            // Form to select the announcement and send the announcementID
        generateFormStart("", "post"); 
            generateFormStartSelectDiv(NULL, "announcementID");

            if ($stmt->num_rows == 0)
            {
                generateFormOption(NULL, NULL, "disabled");
            }
            while($stmt->fetch())
            { 
                generateFormOption($announcementID, $announcementName);
            }  
            generateFormEndSelectDiv();
            generateFormButton("selectAnnouncementButton", "Select Announcement");
        generateFormEnd();
    }   
    else
    {   
    	// There are no active announcements that can be modified
    	echo "<option disabled'>No Active Announcements Available to Modify</option>";
    }
}

?>
