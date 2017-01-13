<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
{
	createAnnouncement($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Announcement could not be created';
   	header('Location: ../../pages/createAnnouncement');

	return;
}

function createAnnouncement($mysqli)
{
	if (isset($_POST['announcementName'], $_POST['announcementPostDate'], $_POST['announcementEndDate'], $_POST['announcementDescription'])) 
	{
    	$announcementName = $_POST['announcementName'];
		$announcementDescription = $_POST['announcementDescription'];
    	$announcementPostDate = $_POST['announcementPostDate'];
		$announcementEndDate = $_POST['announcementEndDate'];

    	if ($stmt = $mysqli->prepare("INSERT INTO announcements (announcementName, announcementDescription, announcementPostDate, announcementEndDate) VALUES (?, ?, ?, ?)"))
		{
    		$stmt->bind_param('ssss', $announcementName, $announcementDescription, $announcementPostDate, $announcementEndDate); 
	    	$stmt->execute();    // Execute the prepared query.
			$_SESSION['success'] = "Announcement Created";
   	   		header('Location: ../../pages/createAnnouncement');
		}
		else
		{
    		// SQL Insertion failed
    		$_SESSION['fail'] = 'Announcement could not be created';
   	   		header('Location: ../../pages/createAnnouncement');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Announcement could not be created, data not sent';
   	   	header('Location: ../../pages/createAnnouncement');
	}
}

?>

