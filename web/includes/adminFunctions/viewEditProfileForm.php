<?php

if (isset($_POST['selectRoleID']))
{
    // After the user selects an class, set it as a $_SESSION variable
    $_SESSION['selectRoleID'] = $_POST['selectRoleID'];
}

if (isset($_POST['gradeID']))
{
    // After the user selects an grade, set it as a $_SESSION variable
    $_SESSION['gradeID'] = $_POST['gradeID'];
}

if (isset($_POST['selectUserID']))
{
    // After the user selects an grade, set it as a $_SESSION variable
    $_SESSION['selectUserID'] = $_POST['selectUserID'];
}

if (isset($_POST['changeRole']))
{
    unset($_SESSION['selectRoleID']);
    unset($_SESSION['selectUserID']);
    unset($_SESSION['gradeID']);
}

if (isset($_POST['changeUser']))
{
    unset($_SESSION['selectUserID']);
}

if (isset($_POST['changeGrade']))
{
    unset($_SESSION['gradeID']);
    unset($_SESSION['selectUserID']);
}

function checkPermissions($mysqli)
{
    if (login_check($mysqli) == true)
    {
        viewEditProfileForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewEditProfileForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	   ';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("Edit User Profile");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#editProfile" data-toggle="tab">Edit Profile</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="editProfile">
                                <br>
        ';
          
                            if (!isset($_SESSION['selectRoleID']))
                            {
                               getRoleLevelForm($mysqli);
                            }
                            else if ((!isset($_SESSION['gradeID']) && ($_SESSION['selectRoleID'] == "5")))
                            {
                                getGradeLevelForm();
                            }
                            else if (isset($_SESSION['gradeID']) && !isset($_SESSION['selectUserID']))
                            {
                                getStudentListForm($_SESSION['gradeID'], $mysqli);
                            }
                            else if (!isset($_SESSION['selectUserID']))
                            {
                                getUserListForm($_SESSION['selectRoleID'], $mysqli);
                            }
                            else 
                            {
                                getUserInfoForm($_SESSION['selectUserID'], $_SESSION['selectRoleID'], $mysqli);
                            }

                            if (isset($_SESSION['selectUserID']))
                            {
                                echo "<br>";
                                generateFormStart("", "post"); 
                                    generateFormButton("changeUser", "Change User");
                                generateFormEnd();
                            } 

                            if (isset($_SESSION['gradeID']))
                            {
                                echo "<br>";
                                generateFormStart("", "post"); 
                                    generateFormButton("changeGrade", "Change Grade Level");
                                generateFormEnd();
                            } 

                            if (isset($_SESSION['selectRoleID']))
                            {
                                echo "<br>";
                                generateFormStart("", "post"); 
                                    generateFormButton("changeRole", "Change Role Level");
                                generateFormEnd();
                            } 
                            
    echo '
                                    
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

function getUserInfoForm($userID, $roleID, $mysqli)
{
    switch ($roleID)
    {
        case 1:
            getAdminProfile($userID, $mysqli);
            break;
        case 2:
            getSchoolAdminProfile($userID, $mysqli);
            break;
        case 3:
            getTeacherProfile($userID, $mysqli);
            break;
        case 4:
            getParentProfile($userID, $mysqli);
            break;
        case 5:
            getStudentProfile($userID, $mysqli);
            break;
    }
}

function getStudentListForm($gradeID, $mysqli)
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Student", "selectUserID");
            getStudentListInfo($gradeID, $mysqli);
        generateFormEndSelectDiv();
        generateFormButton("studentButton", "Select Student");
    generateFormEnd();
}

function getStudentListInfo($gradeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentID, studentFirstName, studentLastName FROM studentProfile WHERE studentGradeLevel = ?"))
    {   
        $stmt->bind_param('i', $gradeID);
        // Query database for announcement details to be modified
        $stmt->execute();

        $stmt->bind_result($dbID, $dbFName, $dbLName);
        
        $stmt->store_result();
        
        if ($stmt->num_rows > 0)
        {
            while ($stmt->fetch())
            {
                 generateFormOption($dbID, "$dbLName, $dbFName");
            }
        }
        else
        {
            generateFormOption(NULL, "No Users available", "disabled", "selected");
        }
    }
    else
    {
        generateFormOption(NULL, "No Users available", "disabled", "selected");
    }
}

function getRoleLevelForm($mysqli)
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Role Type", "roleType");
                    generateFormOption("isAdmin", "Administrator");
        generateFormEndSelectDiv();
        generateFormButton("roleButton", "Select Role Level");
    generateFormEnd();
}

function getGradeLevelForm()
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Grade Level", "gradeID");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);
            }
        generateFormEndSelectDiv();
        generateFormButton("gradeButton", "Select Grade Level");
    generateFormEnd();
}

function getUserListForm($roleID, $mysqli)
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("User", "selectUserID");
            getUserName($userID, $roleID, $mysqli);
        generateFormEndSelectDiv();
        generateFormButton("gradeButton", "Select User");
    generateFormEnd();
}

function getUserName($userID, $roleID, $mysqli)
{
	getUserProfile($userID, $mysqli);

    if ($stmt = $mysqli->prepare("SELECT $id, $fName, $lName FROM $profile"))
    {   
        // Query database for announcement details to be modified
        $stmt->execute();

        $stmt->bind_result($dbID, $dbFName, $dbLName);
        
        $stmt->store_result();
        
        if ($stmt->num_rows > 0)
        {
            while ($stmt->fetch())
            {
                 generateFormOption($dbID, "$dbLName, $dbFName");
            }
        }
        else
        {
            generateFormOption(NULL, "No Users available", "disabled", "selected");
        }
    }
    else
    {
        generateFormOption(NULL, "No Users available", "disabled", "selected");
    }
}

function getUserProfile($userID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName, userEmail FROM users WHERE userID = ?"))
    {   
        // Query database for announcement details to be modified
        $stmt->bind_param('i', $userID);

        $stmt->execute();

        $stmt->bind_result($userFirstName, $userLastName, $userEmail);
        
        $stmt->store_result();
        
        while ($stmt->fetch())
        {
            generateFormStart("../includes/adminFunctions/editProfile", "post");
                generateFormInputDiv("First Name", "text", "userFirstName", $userFirstName);
                generateFormInputDiv("Last Name", "text", "userLastName", $userLastName);
                generateFormInputDiv("Email", "email", "userEmail", $userEmail);
            generateFormEnd();
        }
    }
}

?>
