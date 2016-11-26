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
   	$_SESSION['invalidCreate'] = 'Announcement could not be created';
   	header('Location: ../../pages/createAnnouncement');

	return;
}

function createAnnouncement($mysqli)
{
	if (isset($_POST['announcementTitle'], $_POST['announcementPostDate'], $_POST['announcementEndDate'], $_POST['announcementDescription'])) 
	{
    	$announcementTitle = $_POST['announcementTitle'];
		$announcementDescription = $_POST['announcementDescription'];
    	$announcementPostDate = $_POST['announcementPostDate'];
		$announcementEndDate = $_POST['announcementEndDate'];

    	if ($stmt = $mysqli->prepare("INSERT INTO announcements (announcementTitle, announcementDescription, announcementPostDate, announcementEndDate) VALUES (?, ?, ?, ?)"))
		{
    		$stmt->bind_param('ssss', $announcementTitle, $announcementDescription, $announcementPostDate, $announcementEndDate); 
	    	$stmt->execute();    // Execute the prepared query.
			$_SESSION['createSuccess'] = "Announcement Created";
   	   		header('Location: ../../pages/createAnnouncement');
		}
		else
		{
    		// The correct POST variables were not sent to this page.
    		$_SESSION['invalidCreate'] = 'Announcement could not be created';
   	   		header('Location: ../../pages/createAnnouncement');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['invalidCreate'] = 'Announcement could not be created';
   	   	header('Location: ../../pages/createAnnouncement');
	}
}

?>

