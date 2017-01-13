<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
    {
        viewDeleteAnnouncementForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewDeleteAnnouncementForm($mysqli)
{
    echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
        ';
                        // Call Session Message code and Panel Heading here
                        displayPanelHeading("Delete Announcement");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#deleteAnnouncement" data-toggle="tab">Delete Announcement</a>
                                </li>
                            </ul>

                        <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="deleteAnnouncement">
                                    <h4>Announcement Title, Announcement Post Date</h4>
        ';
										getAnnouncementsForm($mysqli);
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

function getAnnouncementsForm($mysqli)
{
    // Displays a list of announcements for the user
    // Field submits the announcementID that will be deleted
    // In the future this may be replaced with Archived announcements (maybe TODO)
     
	if ($stmt = $mysqli->prepare("SELECT announcementID, announcementPostDate, announcementName FROM announcements"))
    {   
    	$stmt->execute();
        $stmt->bind_result($announcementID, $announcementPostDate, $announcementName);
        $stmt->store_result();

        generateFormStart("../includes/adminFunctions/deleteAnnouncement", "post"); 
            generateFormStartSelectDiv(NULL, "announcementID");
            if ($stmt->num_rows == 0)
            {
                generateFormOption(NULL, "No announcements to delete", "disabled", "selected");
            }
            while($stmt->fetch())
            { 
                generateFormOption($announcementID, "$announcementName, $announcementPostDate");
            }  
            generateFormEndSelectDiv();
            generateFormButton("deleteAnnouncementButton", "Delete Announcement");
        generateFormEnd();
    }   
    else
    {   
    	generateFormOption(NULL, "No announcements to delete", "disabled", "selected");
    }   
}

?>
