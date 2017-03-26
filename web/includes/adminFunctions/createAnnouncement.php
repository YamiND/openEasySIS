<?php
include_once '../dbConnect.php';
include_once '../functions.php';
require_once "../../vendor/autoload.php";
include_once '../customizations.php';


sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli) || isSchoolAdmin($mysqli)))
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
	if ((isset($_POST['announcementName'], $_POST['announcementPostDate'], $_POST['announcementEndDate'], $_POST['announcementDescription'])) && !empty($_POST['announcementName']) && !empty($_POST['announcementPostDate']) && !empty($_POST['announcementEndDate']) && !empty($_POST['announcementDescription']))
	{
    	$announcementName = $_POST['announcementName'];
		$announcementDescription = $_POST['announcementDescription'];
    	$announcementPostDate = $_POST['announcementPostDate'];
		$announcementEndDate = $_POST['announcementEndDate'];
$mail = new PHPMailer;

//From email address and name
$mail->From = emailFrom; //emailFrom is a Constant in customizations.php
$mail->FromName = "Student Information System";

$mail->addAddress(emailFrom);

		if (isset($_POST['sendAllUsers']))
		{
			if ($stmt = $mysqli->prepare("SELECT userEmail FROM users"))
			{
				if ($stmt->execute())
				{
					$stmt->bind_result($userEmail);
					$stmt->store_result();

					while ($stmt->fetch())
					{
						$mail->addBCC("$userEmail");
					}
				}
			}
		}

		if (isset($_POST['sendAllElementary']))
		{
			if ($stmt = $mysqli->prepare("SELECT userID, userEmail FROM users WHERE isStudent and studentGradeLevel <= 6"))
			{
				if ($stmt->execute())
				{
					$stmt->bind_result($userID, $userEmail);

					$stmt->store_result();

					while ($stmt->fetch())
					{
						// Add the student to the email list
						$mail->addBCC("$userEmail");

						if ($stmt2 = $mysqli->prepare("SELECT parentID FROM studentParentIDs WHERE studentID = ?"))
						{
							$stmt2->bind_param('i', $userID);
							
							if ($stmt2->execute())
							{
								$stmt2->bind_result($parentID);
								$stmt2->store_result();

								while ($stmt2->fetch())
								{
									if ($stmt3 = $mysqli->prepare("SELECT userEmail FROM users WHERE userID = ?"))
									{
										$stmt3->bind_param('i', $parentID);

										if ($stmt3->execute())
										{
											$stmt3->bind_result($parentEmail);
											$stmt3->store_result();

											while ($stmt3->fetch())
											{
												// Add Parent's email
												$mail->addBCC("$parentEmail");
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		if (isset($_POST['sendAllHighschool']))
		{
			if ($stmt = $mysqli->prepare("SELECT userID, userEmail FROM users WHERE isStudent and studentGradeLevel > 6"))
			{
				if ($stmt->execute())
				{
					$stmt->bind_result($userID, $userEmail);
					$stmt->store_result();

					while ($stmt->fetch())
					{
						// Add the student to the email list
						$mail->addBCC("$userEmail");

						if ($stmt2 = $mysqli->prepare("SELECT parentID FROM studentParentIDs WHERE studentID = ?"))
						{
							$stmt2->bind_param('i', $userID);
							
							if ($stmt2->execute())
							{
								$stmt2->bind_result($parentID);
								$stmt2->store_result();

								while ($stmt2->fetch())
								{
									if ($stmt3 = $mysqli->prepare("SELECT userEmail FROM users WHERE userID = ?"))
									{
										$stmt3->bind_param('i', $parentID);

										if ($stmt3->execute())
										{
											$stmt3->bind_result($parentEmail);
											$stmt3->store_result();

											while ($stmt3->fetch())
											{
												// Add Parent's email
												$mail->addBCC("$parentEmail");
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

            //Send HTML or Plain Text email
            $mail->isHTML(true);

            $mail->Subject = "MBA SIS Announcement";
            $mail->Body = "<h3>$announcementName<h3><br><p>$announcementDescription</p>";
            $mail->AltBody = "$announcementName, $announcementDescription";

    	if ($stmt = $mysqli->prepare("INSERT INTO announcements (announcementName, announcementDescription, announcementPostDate, announcementEndDate) VALUES (?, ?, ?, ?)"))
		{
    		$stmt->bind_param('ssss', $announcementName, $announcementDescription, $announcementPostDate, $announcementEndDate); 
	    	$stmt->execute();    // Execute the prepared query.

            if(!$mail->send()) 
            {  echo "Mailer Error: " . $mail->ErrorInfo; 
                $_SESSION['fail'] = "Could not email Announcement to recipients";
		exit;
//                header('Location: ../../pages/createAnnouncement');
            }   

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

