<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
{
	updateAnnouncement($mysqli);
}
else
{
   	$_SESSION['invalidUpdate'] = 'Announcement could not be updated, not correct permissions';
   	header('Location: ../../pages/editAnnouncement');

	return;
}

function updateAnnouncement($mysqli)
{
//	if (isset($_POST['announcementTitle'], $_POST['announcementID'], $_POST['announcementPostDate'], $_POST['announcementDescription'])) 
	if(true)
	{
		$announcementID = $_POST['announcementID'];
    	$announcementTitle = $_POST['announcementTitle'];
		$announcementDescription = $_POST['announcementDescription'];
    	$announcementPostDate = $_POST['announcementPostDate'];

		if (!empty($_POST['announcementEndDate']))
		{
			$announcementEndDate = $_POST['announcementEndDate'];
		}
		else
		{
			$announcementEndDate = NULL;
		}

    	if ($stmt = $mysqli->prepare("UPDATE announcements SET announcementTitle = ?, announcementDescription = ?, announcementPostDate = ?, announcementEndDate = ? WHERE announcementID = ?"))
		{
    		$stmt->bind_param('ssssi', $announcementTitle, $announcementDescription, $announcementPostDate, $announcementEndDate, $announcementID); 
	    	$stmt->execute();    // Execute the prepared query.
			$_SESSION['updateSuccess'] = "Announcement Updated";
   	   		header('Location: ../../pages/editAnnouncement');
		}
		else
		{
    		// The correct POST variables were not sent to this page.
    		$_SESSION['invalidUpdate'] = 'Announcement could not be updated, database update failed';
   	   		header('Location: ../../pages/editAnnouncement');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['invalidUpdate'] = 'Announcement could not be updated, variables not sent';
   	   	header('Location: ../../pages/editAnnouncement');
	}
}

?>

