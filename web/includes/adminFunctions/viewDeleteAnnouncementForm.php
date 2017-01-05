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
                                    <form action="../includes/adminFunctions/deleteAnnouncement" method="post" role="form">
                                        <div class="form-group">
                                        	<select class="form-control" name="announcementID">
        ';
												getAnnouncements($mysqli);
	echo '									</select> 
                                        </div>
                                        <button type="submit" class="btn btn-default">Delete Announcement</button>
                                    </form>
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

function getAnnouncements($mysqli)
{
    // Displays a list of announcements for the user
    // Field submits the announcementID that will be deleted
    // In the future this may be replaced with Archived announcements (maybe TODO)

	if ($stmt = $mysqli->prepare("SELECT announcementID, announcementPostDate, announcementName FROM announcements"))
    {   
    	$stmt->execute();
        $stmt->bind_result($announcementID, $announcementPostDate, $announcementName);
        $stmt->store_result();

        while($stmt->fetch())
        {  
			echo "<option value='" . $announcementID . "'>$announcementName, $announcementPostDate</option>";
        }   
    }   
    else
    {   
    	return;
    }   
}

?>
