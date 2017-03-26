<?php
include_once '../dbConnect.php';
include_once '../functions.php';
require_once "../../vendor/autoload.php";
include_once '../customizations.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli) || isSchoolAdmin($mysqli)))
{
	processMassEmail($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Email could not be sent, invalid permissions';
   	header('Location: ../../pages/massEmailForm');

	return;
}

function processMassEmail($mysqli)
{
	if ((isset($_POST['choice'], $_POST['emailFrom'], $_POST['emailBody']) && !empty($_POST['choice']) && !empty($_POST['emailFrom']) && !empty($_POST['emailBody']))
	{
		$choice = $_POST['choice'];
    	$emailFrom = $_POST['emailFrom'];
		$emailBody = $_POST['emailBody'];

		$mail = new PHPMailer;

		//From email address and name
		$mail->From = emailFrom; //emailFrom is a Constant in customizations.php
		$mail->FromName = "Student Information System";

		$mail->addAddress(emailFrom);


		switch ($choice)
		{
			case "0":
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
			break;
			
			case "1":
				if ($stmt = $mysqli->prepare("SELECT userEmail FROM users WHERE isTeacher"))
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
			break;

			case "2":
				if ($stmt = $mysqli->prepare("SELECT userEmail FROM users WHERE isParent"))
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
			break;

			case "3":
				if ($stmt = $mysqli->prepare("SELECT userEmail FROM users WHERE isStudent"))
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
			break;

			case "4":
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
			break;

			case "5":
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
			break;

			case "6":
				if ($stmt = $mysqli->prepare("SELECT DISTINCT userEmail FROM classes, users WHERE classTeacherID = userID AND classGrade <= 6"))
				{
					if ($stmt->execute())
					{
						$stmt->bind_result($teacherEmail);
						$stmt->store_result();

						while ($stmt->fetch())
						{
							// Add Elementary Teacher's email
							$mail->addBCC("$teacherEmail");
						}
					}
				}
			break;

			case "7":
				if ($stmt = $mysqli->prepare("SELECT DISTINCT userEmail FROM classes, users WHERE classTeacherID = userID AND classGrade > 6"))
				{
					if ($stmt->execute())
					{
						$stmt->bind_result($teacherEmail);
						$stmt->store_result();

						while ($stmt->fetch())
						{
							// Add High School Teacher's email
							$mail->addBCC("$teacherEmail");
						}
					}
				}
			break;
		}


            //Send HTML or Plain Text email
            $mail->isHTML(true);

            $mail->Subject = "MBA SIS Mass Email: $emailFrom ";
            $mail->Body = "<p>$emailBody</p>";
            $mail->AltBody = "$emailBody";

            if(!$mail->send()) 
            {  
                $_SESSION['fail'] = "Could not mass email recipients";
                header('Location: ../../pages/massEmailForm');
            }   

			$_SESSION['success'] = "Mass Email Sent";
   	   		header('Location: ../../pages/massEmailForm');
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Mass email could not be sent, data not sent';
   	   	header('Location: ../../pages/massEmailForm');
	}
}

?>

