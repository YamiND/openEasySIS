<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start();

if ((login_check($mysqli) == true) && (isAdmin($mysqli) || isSchoolAdmin($mysqli)))
{
	// If permissions are in check, call the main function
	updateAnnouncement($mysqli);
}
else
{
	// Return generic error message
   	$_SESSION['fail'] = 'Announcement could not be updated, not correct permissions';
   	header('Location: ../../pages/editAnnouncement');

	return;
}

function updateAnnouncement($mysqli)
{
	// Check to see if the POST data was sent correctly
	if (isset($_POST['announcementName'], $_POST['announcementID'], $_POST['announcementPostDate'], $_POST['announcementDescription'], $_POST['announcementEndDate'])) 

	{
		// Assign our POST data to variables
		$announcementID = $_POST['announcementID'];
    	$announcementName = $_POST['announcementName'];
		$announcementDescription = $_POST['announcementDescription'];
    	$announcementPostDate = $_POST['announcementPostDate'];
    	$announcementEndDate = $_POST['announcementEndDate'];

    	if ($stmt = $mysqli->prepare("UPDATE announcements SET announcementName = ?, announcementDescription = ?, announcementPostDate = ?, announcementEndDate = ? WHERE announcementID = ?"))
		{
			// Call our update statement and pass in the new data
    		$stmt->bind_param('ssssi', $announcementName, $announcementDescription, $announcementPostDate, $announcementEndDate, $announcementID); 
	    	$stmt->execute();

	    	// Return to page and give $_SESSION message
			$_SESSION['success'] = "Announcement Edited";
   	   		header('Location: ../../pages/editAnnouncement');
		}
		else
		{
			// Database Update failed, this should never happen
    		// Return to page and give $_SESSION message
    		$_SESSION['fail'] = 'Announcement could not be edited, database update failed';
   	   		header('Location: ../../pages/editAnnouncement');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Announcement could not be edited, data not sent';
   	   	header('Location: ../../pages/editAnnouncement');
	}
}

?>

