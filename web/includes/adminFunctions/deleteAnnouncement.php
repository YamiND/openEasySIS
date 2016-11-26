<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
{
	deleteAnnouncement($mysqli);
}
else
{
   	$_SESSION['deleteFail'] = 'Announcement could not be deleted';
   	header('Location: ../../pages/deleteAnnouncement');

	return;
}

function deleteAnnouncement($mysqli)
{
	if (isset($_POST['announcementID'])) 
	{
    	$announcementID = $_POST['announcementID'];

    	if ($stmt = $mysqli->prepare("DELETE FROM announcements WHERE announcementID = ?"))
		{
    		$stmt->bind_param('i', $announcementID); 
	    	$stmt->execute();    // Execute the prepared query.

			$_SESSION['deleteSuccess'] = "Announcement Deleted";
   	   		header('Location: ../../pages/deleteAnnouncement');
		}
		else
		{
    		// The correct POST variables were not sent to this page.
    		$_SESSION['deleteFail'] = 'Announcement could not be deleted';
   	   		header('Location: ../../pages/deleteAnnouncement');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['deleteFail'] = 'Announcement could not be deleted, data not sent';
   	   	header('Location: ../../pages/deleteAnnouncement');
	}
}

?>

