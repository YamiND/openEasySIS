<?php
include_once '../includes/dbConnect.php';
include_once '../includes/functions.php';
include_once '../includes/panelSessionMessages.php';
include_once '../includes/formTemplate.php';
include_once '../includes/classFunctionsTemplate.php';
include_once '../includes/parentFunctionsTemplate.php';
include_once '../includes/studentFunctionsTemplate.php';
include_once '../includes/userFunctionsTemplate.php';

sec_session_start();

if (login_check($mysqli) == true)
{

    //TODO: Update the aliasOpenEasySIS link to something more appropriate
    //TODO: Update the email link to something more appropriate
    echo '

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">' . aliasOpenEasySIS . '</a>
                </div>
                <!-- /.navbar-header -->
                <ul class="nav navbar-top-links navbar-right">
    				<a href="settings"> ' . htmlentities($_SESSION['userEmail']) . '</a>
					<li>
						<a href="userManual"><i class="fa fa-question-circle fa-fw"></i>Help</a>
					</li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="viewProfile"><i class="fa fa-user fa-fw"></i> My Profile</a></li>
                            <li><a href="settings"><i class="fa fa-gear fa-fw"></i> Settings</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="../includes/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->
                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
            				<li>
				                <a href="#"><i class="fa fa-dashboard fa-fw"></i> Dashboard <span class="fa arrow"></span></a>
    							<ul class="nav nav-second-level">
				    				<li>
    									<a href="dashboard">My Dashboard</a>
    								</li>
				    			</ul>	
           					 </li>
    ';

	if (isAdmin($mysqli))
    {
    echo '
            <li>
                <a href="#"><i class="fa fa-bullhorn fa-fw"></i> Announcements <span class="fa arrow"></span></a>
    			<ul class="nav nav-second-level">
    				<li>
    					<a href="createAnnouncement">Create Announcement</a>
    				</li>
    				<li>
    					<a href="editAnnouncement">Edit Announcement</a>
    				</li>
    				<li>
    					<a href="deleteAnnouncement">Delete Announcement</a>
    				</li>
    				<li>
    					<a href="viewAllAnnouncements">View All Announcements</a>
    				</li>
    			</ul>	
            </li>
            <li>
                <a href="#"><i class="fa fa-child fa-fw"></i> Parents & Children <span class="fa arrow"></span></a>
    			<ul class="nav nav-second-level">
    				<li>
    					<a href="addParentChild">Add Child to Parent</a>
    				</li>
    				<li>
    					<a href="removeParentChild">Remove Child from Parent</a>
    				</li>
    				<li>
    					<a href="viewParentChild">View Parent\'s Children</a>
    				</li>
    			</ul>	
            </li>
            <li>
                <a href="#"><i class="fa fa-user fa-fw"></i> Profiles <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="viewProfile">My Profile</a>
                    </li>
                    <li>
                        <a href="editProfile">Edit User Profile</a>
                    </li>
                </ul>   
            </li>
            <li>
                <a href="#"><i class="fa fa-users fa-fw"></i> Users <span class="fa arrow"></span></a>
    			<ul class="nav nav-second-level">
    				<li>
    					<a href="createUser">Create User (Single)</a>
    				</li>
    				<li>
    					<a href="createBulkUser">Create User (CSV Upload)</a>
    				</li>
    				<li>
    					<a href="adminPasswordReset">Reset User Password</a>
    				</li>
    				<li>
    					<a href="viewUserTables">View All Users</a>
    				</li>
    			</ul>	
            </li>
            <li>
                <a href="#"><i class="fa fa-graduation-cap fa-fw"></i> Classes <span class="fa arrow"></span></a>
    			<ul class="nav nav-second-level">
    				<li>
    					<a href="addClass">Add a Class</a>
    				</li>
                    <li>
                        <a href="assignStudent">Assign Student to Class</a>
                    </li>
                   	<li>
		            	<a href="unAssignStudent">Remove Student From Class</a>
       			    </li>
    				<li>
    					<a href="modifyClass">Modify a Class</a>
    				</li>
    				<li>
    					<a href="deleteClass">Delete a Class</a>
    				</li>
                    <li>
                        <a href="viewStudentListClass">View Student List by Class</a>
                    </li>
    				<li>
    					<a href="viewClassesTable">View All Classes</a>
    				</li>
    			</ul>	
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> School Configuration <span class="fa arrow"></span></a>
    			<ul class="nav nav-second-level">
    				<li>
    					<a href="addSchoolYear">Add School Year</a>
    				</li>
    				<li>
    					<a href="modifySchoolYear">Modify School Year</a>
    				</li>
    				<li>
    					<a href="viewAllSchoolYears">View All School Years</a>
    				</li>
    			</ul>	
            </li>  
        ';
    }
	if (isSchoolAdmin($mysqli) || isAdmin($mysqli))
    {
    echo '
			<li>
          	<a href="#"><i class="fa fa-wrench fa-fw"></i> Report Generation <span class="fa arrow"></span></a>
    			<ul class="nav nav-second-level">
    				<li>
    					<a href="generateTranscript">Generate Transcript</a>
    				</li>
    				<li>
    					<a href="generateReportCard">Generate Report Card</a>
    				</li>
    			</ul>	
            </li>  
        ';
    }

	if (isSchoolAdmin($mysqli))
	{
		echo '
            <li>
                <a href="#"><i class="fa fa-user fa-fw"></i> Profiles <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="lookupStudentProfile">Lookup Student Profile</a>
                    </li>
                    <li>
                        <a href="editStudentProfile">Edit Student Profile</a>
                    </li>
                </ul>   
            </li>
		';
	}	

	if (isTeacher($mysqli))
    {
    echo '
            <li>
                <a href="#"><i class="fa fa-graduation-cap fa-fw"></i> My Classes/Students <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="viewStudentList">View Student List</a>
                    </li>
                    <li>
                        <a href="viewGradeForStudent">View Grades for Student</a>
                    </li>
                    <li>
                        <a href="teacherGradebook">Gradebook</a>
                    </li>
			';
					if (canModClassList($mysqli))
					{
						echo '
                    		<li>
		                        <a href="assignStudent">Assign Student to Class</a>
       			            </li>
						';
					}


echo '
                </ul>   
            </li>
            <li>
                <a href="#"><i class="fa fa-book fa-fw"></i> Assignments <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="addAssignment">Add Assignment</a>
                    </li>
                    <li>
                        <a href="modifyAssignment">Modify Assignment</a>
                    </li>
                    <li>
                        <a href="deleteAssignment">Delete Assignment</a>
                    </li>
                    <li>
                        <a href="viewAllAssignments">View all Assignments</a>
                    </li>
                </ul>   
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Class Configuration <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="addMaterialType">Add Assignment Type</a>
                    </li>
                    <li>
                        <a href="modifyMaterialType">Modify Assignment Type</a>
                    </li>
                    <li>
                        <a href="deleteMaterialType">Delete Assignment Type</a>
                    </li>
                    <li>
                        <a href="viewMaterialTypes">View All Assignment Types</a>
                    </li>
                </ul>   
            </li>
    	';
    }
	if (isParent($mysqli))
    {
    echo '
			<li>
	            <a href="#"><i class="fa fa-graduation-cap fa-fw"></i> Student <span class="fa arrow"></span></a>
   		         <ul class="nav nav-second-level">
       		         <li>
           		         <a href="viewParentStudentGrades">View all Class Grades</a>
               		 </li>
       		         <li>
           		         <a href="viewParentStudentAllAssignments">View all Student Assignments</a>
               		 </li>
	            </ul>   
			</li>
    	';
    }

	if (isStudent($mysqli))
    {
    echo '
            <li>
                    <a href="viewProfile">My Profile</a>
            </li>
            <li>
            <a href="#"><i class="fa fa-graduation-cap fa-fw"></i> Grades <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="viewStudentClassGrades">View all Class Grades</a>
                </li>
            </ul>   
            </li>
                <li>
                <a href="#"><i class="fa fa-book fa-fw"></i> Assignments <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="viewStudentDueAssignments">View Due Assignments</a>
                    </li>
                    <li>
                        <a href="viewStudentAllAssignments">View All Assignments</a>
                    </li>
                </ul>   
            </li>
        ';
    }

	echo '
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>
	';
}
else
{
   	$url = "login"; 
	header("Location:$url");
}
?>
