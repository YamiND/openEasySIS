<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	modifySchoolYear($mysqli);
}
else
{
   	$_SESSION['fail'] = 'School Year could not be updated, not correct permissions';
   	header('Location: ../../pages/modifySchoolYear');
}

function modifySchoolYear($mysqli)
{
	if ((isset($_POST['quarterFourStart'], $_POST['quarterFourEnd'], $_POST['schoolYearStart'], $_POST['schoolYearEnd'], $_POST['fallSemesterStart'], $_POST['fallSemesterEnd'], $_POST['springSemesterStart'], $_POST['springSemesterEnd'], $_POST['quarterOneStart'], $_POST['quarterOneEnd'], $_POST['quarterTwoStart'], $_POST['quarterTwoEnd'], $_POST['quarterThreeStart'], $_POST['quarterThreeEnd'])) && !empty($_POST['schoolYearStart']) && !empty($_POST['schoolYearEnd']) && !empty($_POST['fallSemesterStart']) && !empty($_POST['fallSemesterEnd']) && !empty($_POST['springSemesterStart']) && !empty($_POST['springSemesterEnd']) && !empty($_POST['quarterOneStart']) && !empty($_POST['quarterOneEnd']) && !empty($_POST['quarterTwoStart']) && !empty($_POST['quarterTwoEnd']) && !empty($_POST['quarterThreeStart']) && !empty($_POST['quarterThreeEnd']) && !empty($_POST['quarterFourStart']) && !empty($_POST['quarterFourEnd']))
    {
        $schoolYearID = $_POST['schoolYearID'];
        $schoolYearStart = $_POST['schoolYearStart'];
        $schoolYearEnd =  $_POST['schoolYearEnd']; 
        $fallSemesterStart = $_POST['fallSemesterStart']; 
        $fallSemesterEnd = $_POST['fallSemesterEnd']; 
        $springSemesterStart = $_POST['springSemesterStart']; 
        $springSemesterEnd = $_POST['springSemesterEnd']; 
        $quarterOneStart = $_POST['quarterOneStart']; 
        $quarterOneEnd = $_POST['quarterOneEnd']; 
        $quarterTwoStart = $_POST['quarterTwoStart']; 
        $quarterTwoEnd = $_POST['quarterTwoEnd']; 
        $quarterThreeStart = $_POST['quarterThreeStart'];
        $quarterThreeEnd = $_POST['quarterThreeEnd'];
        $quarterFourStart = $_POST['quarterFourStart'];
        $quarterFourEnd = $_POST['quarterFourEnd'];

        //TODO: Need to add data sanitization comparisons checking code before this if statement
        if ($stmt = $mysqli->prepare("UPDATE schoolYear SET fallSemesterStart = ?, fallSemesterEnd = ?, springSemesterStart = ?, springSemesterEnd = ?, quarterOneStart = ?, quarterOneEnd = ?, quarterTwoStart = ?, quarterTwoEnd = ?, quarterThreeStart = ?, quarterThreeEnd = ?, schoolYearStart = ?, schoolYearEnd = ?, quarterFourStart = ?, quarterFourEnd = ? WHERE schoolYearID = ?"))
        {
            $stmt->bind_param('ssssssssssssssi', $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd, $quarterOneStart, $quarterOneEnd, $quarterTwoStart, $quarterTwoEnd, $quarterThreeStart, $quarterThreeEnd, $schoolYearStart, $schoolYearEnd, $quarterFourStart, $quarterFourEnd, $schoolYearID);
            if ($stmt->execute())
			{
            	$_SESSION['success'] = "School Year Modified Successfully";
            	header('Location: ../../pages/modifySchoolYear');
			}
			else
			{
            	$_SESSION['fail'] = "School Year Modified Unsuccessfully, database could not be updated";
            	header('Location: ../../pages/modifySchoolYear');
			}
        }
    }
    else
    {
        // The correct POST variables were not sent to this page.
        $_SESSION['fail'] = 'School Year could not be modified, please check out all fields';
        header('Location: ../../pages/modifySchoolYear');
    }
}

?>
