<?php

function checkPermissions($mysqli)
{
    if (login_check($mysqli) == true)
    {
        viewProfile($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewProfile($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	   ';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("User Profile");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#createAnnoucement" data-toggle="tab">My Profile</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="myProfile">
                                <br>
        ';
                                getUserInfoForm($_SESSION['userID'], $mysqli);
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

function getUserInfoForm($userID, $mysqli)
{
	// TODO: Adapt this for multiple profile showings....

	if (isAdmin($mysqli))
	{
    	getAdminProfile($userID, $mysqli);
	}

	if (isSchoolAdmin($mysqli))
	{
    	getSchoolAdminProfile($userID, $mysqli);
	}
	
	if (isTeacher($mysqli))
	{
    	getTeacherProfile($userID, $mysqli);
	}

	if (isParent($mysqli))
	{
    	getParentProfile($userID, $mysqli);
	}

	if (isStudent($mysqli))
	{
    	getStudentProfile($userID, $mysqli);
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

        $stmt->bind_result($teacherFirstName, $teacherLastName, $teacherEmail);
        
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

?>
