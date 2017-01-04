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
						if (isset($_SESSION['fail']))
                        {
                        	// Echo fail message and Unset Variable
                        	echo $_SESSION['fail'];
                            unset($_SESSION['fail']);
                        }
						else if (isset($_SESSION['success']))
						{
							// Echo success message and Unset Variable
                        	echo $_SESSION['success'];
                            unset($_SESSION['success']);
						}
                        else
                        {
                        	// Echo default message
                        	echo 'Edit Announcement';
                        }
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
	echo '
			<br>
			<form action="" method="post" role="form">
				<button type="submit" class="btn btn-default" name="changeAnnouncement">Change Announcement</button> 
			</form>
	   	';
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

        // Output form that contains the DB variables
        echo '
		<form action="../includes/adminFunctions/updateAnnouncement" method="post" role="form">
				<input type="hidden" name="announcementID" value="'.$announcementID.'">
				<div class="form-group">
					<input class="form-control" name="announcementName" value="' . $announcementName . '">
				</div>
				<div class="form-group">
					<label>Announcement Post Date</label>
					<input class="form-control" type="date" name="announcementPostDate" value="' . $announcementPostDate . '">
				</div>
				<div class="form-group">
					<label>Announcement End Date</label>
					<input class="form-control" type="date" name="announcementEndDate" value="' . $announcementEndDate . '">
				</div>
				<div class="form-group">
					<label>Announcement Description</label>
					<textarea class="form-control" name="announcementDescription" rows="5">' . $announcementDescription . '</textarea>
				</div>
				<button type="submit" class="btn btn-default">Edit Announcement</button>
		</form>
			';
    }  
}

function getAnnouncementsForm($mysqli)
{
	// Form to select the announcement and send the announcementID
	echo '
		<form action="" method="post" role="form">
			<div class="form-group">
				<select class="form-control" name="announcementID">';								
	if ($stmt = $mysqli->prepare("SELECT announcementID, announcementPostDate, announcementName FROM announcements WHERE announcementEndDate >= curdate()"))
    {   
    	// Query and Display all available announcements
    	$stmt->execute();
        $stmt->bind_result($announcementID, $announcementPostDate, $announcementTitle);
        $stmt->store_result();

        while($stmt->fetch())
        { 
			echo "<option value='" . $announcementID . "'>$announcementTitle, $announcementPostDate</option>";
        }  
    }   
    else
    {   
    	// There are no active announcements that can be modified
    	echo "<option disabled'>No Active Announcements Available to Modify</option>";
    }

    echo '
			</select> 
			     </div>
			<button type="submit" class="btn btn-default">Select Announcement</button>
			</form>
		'; 
}

?>
