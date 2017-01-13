<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1) && (isset($_POST['announcementID'])))
{
	selectAnnouncement($mysqli);
}
else
{
   	$_SESSION['invalidCreate'] = 'Announcement could not be created';
   	header('Location: ../../pages/editAnnouncement');

	return;
}

function selectAnnouncement($mysqli)
{
	$announcementID = $_POST['announcementID'];

    if ($stmt = $mysqli->prepare("SELECT announcementPostDate, announcementEndDate, announcementTitle, announcementDescription FROM announcements WHERE announcementID = ?"))
    {   
    	$stmt->bind_param('i', $announcementID); 
        $stmt->execute();
        $stmt->store_result();

		if ($stmt->num_rows == 1)
		{
        	$stmt->bind_result($announcementPostDate, $announcementEndDate, $announcementTitle, $announcementDescription);
			$stmt->fetch();

			$_SESSION['announcementID'] = "$announcementID";
			$_SESSION['announcementTitle'] = "$announcementTitle";
			$_SESSION['announcementDescription'] = "$announcementDescription";
			$_SESSION['announcementPostDate'] = "$announcementPostDate";
			$_SESSION['announcementEndDate'] = "$announcementEndDate";
	
			$_SESSION['announcementSelected'] = "Announcement Selected";
   	   		header('Location: ../../pages/editAnnouncement');
		}
    	else
    	{  
			$_SESSION['invalidSelect'] = "Could not select Announcement";
   	   		header('Location: ../../pages/editAnnouncement');
    	}   
    }   
    else
    {  
		$_SESSION['invalidSelect'] = "Could not select Announcement";
   	   	header('Location: ../../pages/editAnnouncement');
    }   
}

?>

