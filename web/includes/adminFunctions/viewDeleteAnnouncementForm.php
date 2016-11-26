<?php
include_once '../dbConnect.php';

date_default_timezone_set('America/New_York');

function viewDeleteAnnouncementForm($mysqli)
{
echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
    ';
                        if (isset($_SESSION['deleteFail']))
                        {
                            echo $_SESSION['deleteFail'];
                            unset($_SESSION['deleteFail']);
                        }
                        else if (isset($_SESSION['deleteSuccess']))
                        {
                            echo $_SESSION['deleteSuccess'];
                            unset($_SESSION['deleteSuccess']);
                        }
                        else
                        {   
                            echo 'Delete Announcement';
                        }   
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
                                        	<select class="form-control" name="announcementID">';
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

	if ($stmt = $mysqli->prepare("SELECT announcementID, announcementPostDate, announcementTitle FROM announcements"))
    {   
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
    	return;
    }   
}

?>
