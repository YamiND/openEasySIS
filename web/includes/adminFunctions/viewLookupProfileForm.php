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
        viewLookupProfileForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewLookupProfileForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	   ';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("Lookup User Profile");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#lookupProfile" data-toggle="tab">Lookup Profile Profile</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="lookupProfile">
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
        generateFormStartSelectDiv("Role Type", "selectRoleID");
        if ($stmt = $mysqli->prepare("SELECT roleID, roleName FROM roles"))
        {   
            // Query database for announcement details to be modified
            $stmt->execute();

            $stmt->bind_result($roleID, $roleName);
            
            $stmt->store_result();
            
            if ($stmt->num_rows > 0)
            {
                while ($stmt->fetch())
                {
                    generateFormOption($roleID, $roleName);
                }
            }
            else
            {
                generateFormOption(NULL, "No roles available", "disabled", "selected");
            }
        }
        else
        {
            generateFormOption(NULL, "No roles available", "disabled", "selected");
        }
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
    switch ($roleID)
    {
        case 1:
            $profile = "adminProfile";
            $fName = "adminFirstName";
            $lName = "adminLastName";
            $id = "adminID";
            //getAdminProfile($userID, $mysqli);
            break;
        case 2:
            $profile = "schoolAdminProfile";
            $fName = "schoolAdminFirstName";
            $lName = "schoolAdminLastName";
            $id = "schoolAdminID";
            //getSchoolAdminProfile($userID, $mysqli);
            break;
        case 3:
            $profile = "teacherProfile"; 
            $fName = "teacherFirstName";
            $lName = "teacherLastName";
            $id = "teacherID";
            //getTeacherProfile($userID, $mysqli);
            break;
        case 4:
            $profile = "parentProfile";
            $fName = "parentFirstName";
            $lName = "parentLastName";
            $id = "parentID";
            //getParentProfile($userID, $mysqli);
            break;
        case 5:
            $profile = "studentProfile";
            $fName = "studentFirstName";
            $lName = "studentLastName";
            $id = "studentID";
            //getStudentProfile($userID, $mysqli);
            break;
    }

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

function getAdminProfile($userID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT adminFirstName, adminLastName, adminEmail FROM adminProfile WHERE adminID = ?"))
    {   
        // Query database for announcement details to be modified
        $stmt->bind_param('i', $userID);

        $stmt->execute();

        $stmt->bind_result($adminFirstName, $adminLastName, $adminEmail);
        
        $stmt->store_result();
        
        while ($stmt->fetch())
        {
            generateFormStart();
                generateFormInputDiv("First Name", "text", "adminFirstName", $adminFirstName, "disabled");
                generateFormInputDiv("Last Name", "text", "adminLastName", $adminLastName, "disabled");
                generateFormInputDiv("Email", "email", "adminEmail", $adminEmail, "disabled");
            generateFormEnd();
        }
    }
}

function getSchoolAdminProfile($userID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT schoolAdminFirstName, schoolAdminLastName, schoolAdminEmail FROM schoolAdminProfile WHERE schoolAdminID = ?"))
    {   
        // Query database for announcement details to be modified
        $stmt->bind_param('i', $userID);

        $stmt->execute();

        $stmt->bind_result($schoolAdminFirstName, $schoolAdminLastName, $schoolAdminEmail);
        
        $stmt->store_result();
        
        while ($stmt->fetch())
        {
            generateFormStart();

                generateFormInputDiv("First Name", "text", "schoolAdminFirstName", $schoolAdminFirstName, "disabled");
                generateFormInputDiv("Last Name", "text", "schoolAdminLastName", $schoolAdminLastName, "disabled");
                generateFormInputDiv("Email", "email", "schoolAdminEmail", $schoolAdminEmail, "disabled");
                
            generateFormEnd();
        }
    }
}

function getTeacherProfile($userID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT teacherFirstName, teacherLastName, teacherEmail FROM teacherProfile WHERE teacherID = ?"))
    {   
        // Query database for announcement details to be modified
        $stmt->bind_param('i', $userID);

        $stmt->execute();

        $stmt->bind_result($teacherFirstName, $teacherFirstName, $teacherEmail);
        
        $stmt->store_result();
        
        while ($stmt->fetch())
        {

            generateFormStart();

                generateFormInputDiv("First Name", "text", "teacherFirstName", $teacherFirstName, "disabled");
                generateFormInputDiv("Last Name", "text", "teacherLastName", $teacherLastName, "disabled");
                generateFormInputDiv("Email", "email", "teacherEmail", $teacherEmail, "disabled");

            generateFormEnd();
        }
    }
}

function getParentProfile($userID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT parentFirstName, parentLastName, parentEmail, parentPhoneNumber, parentAltEmail, parentAddress, parentCity, parentState, parentZip FROM parentProfile WHERE parentID = ?"))
    {   
        // Query database for announcement details to be modified
        $stmt->bind_param('i', $userID);

        $stmt->execute();

        $stmt->bind_result($parentFirstName, $parentLastName, $parentEmail, $parentPhoneNumber, $parentAltEmail, $parentAddress, $parentCity, $parentState, $parentZip);
        
        $stmt->store_result();
        
        while ($stmt->fetch())
        {
                generateFormStart();

                    generateFormInputDiv("First Name", "text", "parentFirstName", $parentFirstName, "disabled");
                    generateFormInputDiv("Last Name", "text", "parentLastName", $parentLastName, "disabled");
                    generateFormInputDiv("Email", "email", "parentEmail", $parentEmail, "disabled");

                    if (!empty($parentPhoneNumber))
                    {
                        generateFormInputDiv("Phone Number", "tel", "parentPhoneNumber", $parentPhoneNumber, "disabled");
                    }

                    if (!empty($parentAltEmail))
                    {
                        generateFormInputDiv("Alt Email", "email", "parentAltEmail", $parentAltEmail, "disabled");
                    }

                    generateFormInputDiv("Address", "text", "parentAddress", $parentAddress, "disabled");
                    generateFormInputDiv("City", "text", "parentCity", $parentCity, "disabled");
                    generateFormInputDiv("State", "text", "parentState", $parentState, "disabled");
                    generateFormInputDiv("Zip", "number", "parentZip", $parentZip, "disabled");


                    generateFormStartSelectDiv("Students", "students");
                        getStudentList($userID, $mysqli);
                    generateFormEndSelectDiv();

                generateFormEnd();
        }
    }
}

function getStudentProfile($userID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName, studentEmail, studentBirthdate, studentGender, studentGradYear, studentGPA, studentGradeLevel FROM studentProfile WHERE studentID = ?"))
    {   
        // Query database for announcement details to be modified
        $stmt->bind_param('i', $userID);

        $stmt->execute();

        $stmt->bind_result($studentFirstName, $studentLastName, $studentEmail, $studentBirthdate, $studentGender, $studentGradYear, $studentGPA, $studentGradeLevel);
        
        $stmt->store_result();
        
        while ($stmt->fetch())
        {

            generateFormStart();

                generateFormInputDiv("First Name", "text", "studentFirstName", $studentFirstName, "disabled");
                generateFormInputDiv("Last Name", "text", "studentLastName", $studentLastName, "disabled");
                generateFormInputDiv("Email", "email", "studentEmail", $studentEmail, "disabled");
                generateFormInputDiv("Birthdate", "text", "studentBirthdate", $studentBirthdate, "disabled");
                generateFormInputDiv("Gender", "text", "studentGender", $studentGender, "disabled");
                generateFormInputDiv("Graduation Year", "number", "studentGradYear", $studentGradYear, "disabled");
                generateFormInputDiv("GPA", "number", "studentGPA", $studentGPA, "disabled");
                generateFormInputDiv("Grade Level", "number", "studentGradeLevel", $studentGradeLevel, "disabled");

            generateFormEnd();
        }
    }
}


function getStudentList($userID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentID FROM studentParentIDs WHERE parentID = ?"))
    {   
        // Query database for announcement details to be modified
        $stmt->bind_param('i', $userID);

        $stmt->execute();

        $stmt->bind_result($studentID);
        
        $stmt->store_result();
        
        while ($stmt->fetch())
        {
            getStudentName($studentID, $mysqli);
        }
    }
}

function getStudentName($studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName FROM studentProfile WHERE studentID = ?"))
    {   
        // Query database for announcement details to be modified
        $stmt->bind_param('i', $studentID);

        $stmt->execute();

        $stmt->bind_result($studentFirstName, $studentLastName);
        
        $stmt->store_result();
        
        while ($stmt->fetch())
        {
            generateFormOption("studentName", "$studentLastName, $studentFirstName");
        }
    }
}

?>
