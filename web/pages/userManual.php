<?php

// Our constants
include("../includes/customizations.php");

// Our admin functions
include('../includes/userFunctions/viewAnnouncements.php'); 
include('../includes/adminFunctions/viewTotalAccounts.php'); 
include('../includes/adminFunctions/viewTotalClasses.php'); 

echo '
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <title>' . aliasOpenEasySIS . ' - User Manual</title>
            <!-- Header Information, CSS, and JS -->
            ';

            include("../includes/header.php");
    echo '
        </head>

        <body>

            <div id="wrapper">

        	<!-- Navigation Menu -->
        ';
                include('../includes/navPanel.php'); 
    echo '
                <div id="page-wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">User Manual</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
            ';
			
	if (isAdmin($mysqli))
    {
	echo '
		  <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            This is the Admin User Manual
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1 - Dashboard </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
										<h4>1.1 - My Dashboard</h4>
										<p>
                                            The first thing the administrator will see is the announcements widget, which gives the information of the Date, Name, and Descriptions of all announcements. Creation of these announcements will be explained further in the manual. 
										</p>
										<p>
											Also show, are 5 boxes. These boxes include Administrators, Students, Teachers, Parents, and Classes. When the administrator selects the “View Details link”, they are taken to a page called “User Table”. From here, they have access to view all of the users of the SIS. Details include their First Name, Last Name, and Email that is registered to the SIS. The administrator can see who is on their system, and who still needs to be added, updated, or deleted. 
										</p>
										<p>	
											The 5th box, called “View Classes”, allows the administrator to view all of the classes that are offered/registered to the Maplewood Baptist Academy. 
										</p>
										
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2 - Announcements</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>2.1 - Create Announcement</h4>
										<p>
											The first option given is “create an announcement”.  The administrator will be given 4 text boxes to input information.
										</p>
										<p>
											The administrator will need to give the announcement a name, and the date in which the announcement was created and when the planned event of the announcement will end. When the administrator hovers over the announcement post date and end date, on the far right section of the textbox, is a drop arrow. By selecting this, a calendar will appear, and the administrator can easily find the day and month to select. 	 
										</p>
										<p>	
											End Date, allows the administrator to decide when they would wish for the announcement to be automatically deleted from the SIS. The record of it will still viewable on the “View All Announcements” page, however, other users of the SIS will no longer see it when the end date has come to pass.  
										</p>
										<p>
											The last text box, named “Description”, is where the administrator can type in detail on what the announcement is about, and any other key information needed.  	 
										</p>
										<p>	
											Once finished, select the “Create Announcement” button on the bottom of the page, and display the announcement to all faculty, parents, and students.   
										</p>
										</br>
										<h4>2.2 - Edit Announcement</h4>
										<p>
											Edit Announcement allows the user to change any information on previously created announcements. Corrections to a date, name, typo, add more information to description, etc. 
										</p>
										<p>
											When you select the tab “Edit Announcement”, it will take you to a page with a textbox and a button called “Select Announcement”. When you click on the first text box, it will display a list of all announcements currently being shown on the SIS. Select the announcement you wish to edit, then click the “Select Announcement” button to start editing. 	 
										</p>
										<p>	
											This will open up the information similar to “Create Announcement”, and from here the administrator can start to fix, change, update, etc. anything needed for the announcement.   
										</p>
										<p>
											Once finished, the administrator will then click the button “Edit announcement” at the bottom of the page, and the updated announcement will take the place of the old announcement. 	 
										</p>
										</br>
										<h4>2.3 - Delete Announcement</h4>
										<p>
											The delete announcement tab, allows the Administrator to delete any announcements that have been created. This applies for both expired announcements, and current announcements still viewable.  
										</p>
										<p>
											Under “Announcement Title, Announcement Post Date”, select the announcement you wish to delete, then click the button “Delete Announcement”.  	 
										</p>
										<p style="color:red;">	
											*Warning* once you delete an announcement, you will be unable to retrieve it later.   
										</p>
										</br>
										<h4>2.4 - View All Announcements</h4>
										<p>
											“View All announcements”, allows the administrator to view all announcements, whether expired or still viewable, that have been published on the student information system for Maplewood Baptist Academy. 
										</p>

                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3 - Profiles</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>3.1 - My Profile</h4>
										<p>
											The “My Profile” page allows the current user to view their profile information. This information includes the First Name, Last Name, and Email of the user.
										</p>
										<h4>3.2 - Edit a Profile</h4>
										<p>
											The “Edit a Profile” page allows the administrator to edit a profile. The first thing the administrator is presented with, is the Role Type text box. From here, the administrator selects what kind of role the administrator would like to edit. So, if the administrator would like to edit a student’s profile, they would select “Role Type: Student”. This will bring up a list of all students, and from there, the administrator would select the user desired, and be allowed the option to edit the first name, last name, or email address.
										</p>
										<h4>3.3 - Lookup Profile</h4>
										<p>
											This allows the administrator to look up any profile to gather basic information on a user. Similar to the “Edit a Profile” page, the administrator will select which role the user has, and from there, a list of all users defined to that role type will be displayed, along with their information. 
										</p>
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">4 - Users</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>4.1 - Create User (Single)</h4>
										<p>
											If the administrator needs to create a single, or few users, then they should select the option “Create User (Single)”.  Once select, at the top of the field, they will have the option to select between Administrator, School Administrator, Teacher, Parent, or Student. This will define what permissions and privileges these users will have on the Student Information System. 
										</p>
										<p>
											ADMINISTRATOR:  The highest level possible, and should only be allowed for Faculty that are in charge of the Academy. The Administrator has full access to all users on the SIS. They are to enter their email address, first name, and last name. After entering in the information, the administrator is presented with three check boxes. Modify Profiles, Modify Class Lists, and View All Grades. Select the permissions appropriate for the administrator, then select “Create Administrator”.
										</p>
										<p>
											SCHOOL ADMINISTRATOR: Similar to the Administrator, School Administrator is reserved for those in charge, and should be only given to appropriate faculty members. Enter the information pertaining to the Email, First Name, Last Name, and then apply appropriate permissions via the three text boxes below. Once finished, select “Create School Administrator”.
										</p>
										<p>
											TEACHER: The teacher option allows for the creation of faculty for the school. The Email, First Name, and Last Name should be entered in the text boxes below.
										</p>
										<p>
											Below the text boxes, are three check boxes, defined as Modify Profiles, Modify Class Lists, and View All Grades. Depending on the trust and job requirements for the teachers, it is up to the administrator to give appropriate permissions to the teachers. Select which boxes the teacher will have permission to do, then select “Create Teacher”.
										</p>
										<p>
											PARENT: The parent option allows the administrator to create a profile for the parents of any student that attends Maplewood Baptist Academy. There are 5 text boxes that require information, these include Email, First Name, Last Name, Address, and City.
										</p>
										<p>
											Once this information is filled out, the administrator will then have to select which state the parent is from, and select “Create Parent”. 
										</p>
										<p>
											STUDENT: Lastly, the student option allows the creation of students for the academy. 3 text boxes require information, these include; Email, First Name, and Last Name. Once finished the administrator will have to select the Gender, either Male or Female. Then the administrator will select the grade level of the student via the drop box. Options range from 1st grade, up to 12th grade. Select the appropriate grade, then select “Create Student”. 
										</p>
										<h4>4.2 - Create Bulk User (CSV)</h4>
										<p>
											The “Create Bulk User (CSV)” option, allows the administrator the ability to add a large amount of users at once to the academy, and saves time and work. 
										</p>
										<p>
											The first question is, what is a CSV? 
										</p>
										<p>
											CSV stand for Comma Separated Values. It is a text file, which follows a specific criteria. A CSV document can be created easily in any word document software available.  It MUST be saved as a “.csv” document, and not a “.txt” document.  Below are pictures demonstrating creating a CSV file, and how to save it correctly to be used 
										</p>
										<p>
											This is the first page offered when the administrator selects the “Create Bulk User (CSV)” tab. 
										</p>
										<p>
											From the front page, the admin is offered 5 choices. Students, Parents, Teachers, School Admins, and Admins. Depending on what type of roles the admin requires (i.e. students, teachers, etc.) The admin will select that tab. 
										</p>
										<p>
											Each tab has instructions on how to create/format the .csv file. There is also a link that will take the admin to Microsoft support office, which will take the user step by step on the procedures for importing and exporting .csv files. 
										</p>
										<p>
											The example this manual will use, will show the administrator how to create a .csv file for a list of students.  
										</p>
										<p>
											From here, we see that the format for a comma separated value file for students abides by the following format:
										</p>
										<p>
											Student Email, Student First Name, Student Last Name, Grade Level 
										</p>
										<p>
											Opening a text editor (notepad, Microsoft word, etc.), we can create a text document with the information required.  
										</p>
										<p>
											In the above image, using Notepad, A list of three users were created. Following the format specified on the page “Create Bulk Users (CSV)”, the file consists of the students email address, their first name, and last name, followed by their grade. Each one separated by a COMMA. 
										</p>
										<p>
											When finished. Go to File, Save, and when it prompts you to save the File name. At the end of whichever name you give the file (could be by class name, class year, etc.), you must add the “.csv”. This will automatically generate it as a .csv file. 
										</p>
										<p>
											Once finished, go back to the main page, and while selecting button “choose file”.
										</p>
										<p>
											Select from the directory, the created .csv file. Once chosen, submit the file to the Information System, by selecting the button “Upload CSV and Create Students”.
										</p>
										<p>
											The same basic principles will be applied to all other roles, when creating users. The specific formatting for the .csv files will be found at the bottom of each roles tabs 
										</p>
										<h4>4.3 - Reset User Password</h4>
										<p>
											This allows the administrator to reset any user’s password, in case they have forgotten, or there has been a breach in the system. 
										</p>
										<h4>4.4 - View All User</h4>
										<p>
											This allows the administrator to view all users on the system. Their first name, last name, and email are available. 
										</p>
										<p>
											A search text box is also available in the top right corner, to allow a quick search via the user’s name.  
										</p>
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">5 - Classes</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        <h4>5.1 - Add a Class</h4>
										<p>
											The first option available. The “Add a Class” feature allows the administrator to create classes, based on name, grade level, and which teacher is assigned to teach that class.  
										</p>
										<p>
											The administrator will need to first fill out the class name, in the text box provided. Once complete, the administrator will then select the grade level (1-12), and from the drop down box, select which teacher will teach said class. The teacher must already have been created for them to show up on the drop down box, and if the teacher is not available, the administrator will need to go back to Users, and create a new account, under the “Create User (Single)”, and select the job position of Teacher.   
										</p>
										<p>
											Once finished, the administrator will select the button, “Add Class”, and the new class will be created, and viewable via the “View All Classes” option.  
										</p>
										<h4>5.2 - Assign Student to Class</h4>
										<p>
											“Assign Student to Class”, allows the administrator, and any faculty given the permissions, to assign students of Maplewood Baptist Academy to a class.  
										</p>
										<p>
											The first thing the administrator will see, is a prompt to select a grade level. Choose the grade level for the class, in which you would like to add a student to. 
										</p>
										<p>
											Once selected, it will offer the option of “Select Class”, from a drop down box. Click the text box, and a list of classes that are offered for that grade level will be presented to the administrator. Once you have selected the class, click on the button “Select Class”.  
										</p>
										<p>
											You will then be brought to a screen in which you will be able to select from a list of students that are in the grade level, associated with the class. Once you have selected a student, select the button “Add Student to Class”, and the selected student will then be added to the class. 
										</p>
										<h4>5.3 - Modify Class</h4>
										<p>
											“Modify Class” allows the administrator to make changes to any class that has already been added to the Student Information System.  
										</p>
										<p>
											The first option available, is a drop box called “Class Name”. This will open a list of all classes offered at the Academy, and the administrator can then select which class they wish to edit.  
										</p>
										<p>
											Once the correct class has been selected, the administrator will then have the option to edit the class name, which grade level the class is offered for, and the current teacher that is in charge.  
										</p>
										<p>
											Once the information has been updated to the administrator’s liking, the administrator can then select “Modify Class Information”, and the new changes will be updated to the SIS.  
										</p>
										<h4>5.4 - Delete Class</h4>
										<p>
											The “Delete a Class” option, allows the administrator to delete any class that is no longer being taught or needed, at the Maplewood Baptist Academy. Simply select which class you would like to delete, via the Class Name drop box, and then select the button “Delete Class” to wipe the class from the SIS.   
										</p>
										<p style="color:red;">
											*If the class you are looking to delete isn’t available, you may need to change the grade level, via the button “Change Grade Level”. 
										</p>
										<h4>5.5 - View Student List by Class</h4>
										<p>
											“View Student List by Class”, allows the administrator to conveniently view every student that is registered to a class.  
										</p>
										<p>
											Select the grade level, and class name, to have a list displayed of all current students in the class. The list displays the first name, last name, and email for each student.  
										</p>
										<h4>5.6 - View All Classes</h4>
										<p>
											This option allows the administrator to view all classes offered at Maplewood Baptist Academy, regardless of grade level. It will display a list containing the class name, grade level, and the email for the teacher that is in charge of the class.  
										</p>
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">6 - School Configuration</a>
                                        </h4>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        <h4>6.1 - Add School Year</h4>
										<p>
											This allows the creation of a new school year. On this page, the administrator will need to add the dates for the following: 
										</p>
										<li>School year start/end date</li>
										<li>Fall semester start/end date</li>
										<li>Spring semester start/end date</li>
										<li>Quarter 1, 2, 3 start/end date</li>
										<p>
											Once the correct dates have been entered for the school year, the administrator will then select the button “Add school year”, and the new school year will be created.
										</p>
										<h4>6.2 - Modify School Year</h4>
										<p>
											This option will allow the administrator to update or fix any dates that may have been incorrect during the creation of the school year. From this page, the administrator will fill out the new modified school year dates, including:
										</p>
										<li>School year start/end date</li>
										<li>Fall semester start/end date</li>
										<li>Spring semester start/end date</li>
										<li>Quarter 1, 2, 3 start/end date</li>
										<p>
											Once complete, the administrator will need to select the button “Modify school year”, and the new updates will be applied to the school year that the administrator selected to modify. 
										</p>
										<h4>6.3 - View All School Years</h4>
										<p>
											“View all school years” allows the administrator to view all current and previous school years for the Maplewood Baptist Academy. Displayed is a start and end date for the school year, fall semester, spring semester, and the three quarters of the year. 
										</p>
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">7 - Report Generation</a>
                                        </h4>
                                    </div>
                                    <div id="collapseSeven" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        <h4>7.1 - Generate Transcript</h4>
										<p>
											“Generate Transcript” allows for the administrator to print out a student’s entire Report Card history during their attendance at Maplewood Baptist Academy. The administrator has three methods to choose from: Generate Single Transcript, Generate Transcripts for Specific Grade, and Generate Transcripts for All Grades.  
										</p>
										<p>
											Generate Single Transcript: when this option is selected, the administrator will choose the desired student’s grade, from the drop box. This will present a list via the new drop box of every student in that grade. Once the student is selected, click “Choose Student”. This will download a zip file. The zip file contains a list of directories, one for each grade level the student has attended. Inside each directory, contains an Adobe file that depicts that year’s report card. 
										</p>
										<p>
											Generate Transcripts for Specific Grade: This allows the admin to generate transcripts for every student that is currently attending the selected grade. Chosen via the drop box. Similar to single transcript, a zip file containing the grade level, will have every current student of said grade level’s transcript, during their duration at Maplewood Baptist Academy.
										</p>
										<p>
											Generate Transcripts for All Grades: This option will generate a transcript for every student currently attending Maplewood Baptist Academy. 
										</p>
										<h4>7.2 - Generate Report Card</h4>
										<p>
											“Generate Report Card” allows the administrator to generate report cards of the students that attend Maplewood Baptist Academy.  
										</p>
										<p>
											The administrator will have three methods to choose from: Generate Single Report Card, Generate Report Cards for Specific Grade, and Generate Report Cards for All Grades. 
										</p>
										<p>
											Generate Single Report Card: when this option is selected, the administrator will choose which grade level the student currently is, and then to select the specific student from the drop box list of all students currently in the selected grade bracket. Once the student needed has been selected, the administrator will select the button “Choose Student”, afterwards, a zip file will be downloaded to the computer. Inside the zip file will be a folder containing an Adobe file. 
										</p>
										<p>
											Generate Report Cards for Specific Grade: When this option is selected, the administrator will be able to generate all report cards for students assigned to a specific grade level. The administrator will be given the option to select which grade from the drop box report cards will generate for. 
										</p>
										<p>
											Similar to single report card generation, a zip file will be downloaded to the admin’s computer, containing a list of all students’ report cards.
										</p>
										<p>
											Generate Report Cards for All Grades: This allows the admin to quickly generate a report card for every student in the school. A zip file will be downloaded, containing a list of directories (one per grade). Each directory will contain every student in that grade level, along with their report card.
										</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->	
			';
	}
	if (isSchoolAdmin($mysqli))
    {
	echo '
		  <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            This is the School Admin User Manual
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Collapsible Group Item #1</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Collapsible Group Item #2</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Collapsible Group Item #3</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->	
			';
	}
	if (isTeacher($mysqli))
    {
	echo '
		  <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            This is the Teacher User Manual
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Collapsible Group Item #1</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Collapsible Group Item #2</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Collapsible Group Item #3</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->	
			';
	}
	if (isParent($mysqli))
    {
	echo '
		  <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            This is the Parent User Manual
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Collapsible Group Item #1</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Collapsible Group Item #2</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Collapsible Group Item #3</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->	
			';
	}
	if (isStudent($mysqli))
    {
	echo '
		  <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            This is the Student User Manual
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Collapsible Group Item #1</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Collapsible Group Item #2</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Collapsible Group Item #3</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->	
			';
	}
echo '

                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

        </body>

        </html>
    ';
?>

	