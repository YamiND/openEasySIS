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
                                            The first thing the administrator will see is the announcements widget, which gives you the Date, Name, and Descriptions of all announcements. Creation of these announcements will be explained further in the manual. 
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
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2 - Parents & Children</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>2.1 - Add Child to Parent</h4>
										<p>
											Once student and parents accounts have been crated they can be link together here.  First you will select the grade level of the student from the drop down list and click the "Choose Grade Level" button.  Then you will select the student and parent from the drop down list respectively and click the "Add Student to Parent" button.  You can also change the student by clicking on the "Change Grade" button.   
										</p>
										</br>
										<h4>2.2 - Remove Child from Parent</h4>
										<p>
											First select the parent from the drop down list and click on the "Choose Parent" button.  Then you will select a student from the drop down list and click on the "Remove Student from Parent" button.  You can also change the parent by clicking on the "Change Parent" button.
										</p>
										</br>
										<h4>2.3 - View Parent\'s Children</h4>
										<p>
											Here you will see a table that includes a list of parents along with a list of their children(s).  
										</p>
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3 - Users</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>3.1 - Create User (Single)</h4>
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
										<h4>3.2 - Create Bulk User (CSV)</h4>
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
										<h4>3.3 - Delete User</h4>
										<p style="color:red;">
											Deleting a user should not be taken lightly. This will completely wipe them out of the system
										</p>
										<p>
											To delete a user from the system choose the appropriate account from the drop down list and click "Delete User".
										</p>
										<h4>3.4 - Reset User Password</h4>
										<p>
											This allows the you to reset any user’s password.  Enter in the Users email addess and click "Submit".  You will see their new password above the email field.  They will also receive an email with their new password.   
										</p>
										<h4>3.5 - View All User</h4>
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
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">4 - School Configuration</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        <h4>4.1 - Add School Year</h4>
										<p>
											This allows the creation of a new school year. On this page, the administrator will need to add the dates for the following: 
										</p>
										<li>School year start/end date</li>
										<li>Fall semester start/end date</li>
										<li>Spring semester start/end date</li>
										<li>Quarter 1, 2, 3 start/end date</li>
										</br>
										<p>
											Once the correct dates have been entered for the school year, the administrator will then select the button “Add school year”, and the new school year will be created.
										</p>
										<h4>4.2 - Modify School Year</h4>
										<p>
											This option will allow the administrator to update or fix any dates that may have been incorrect during the creation of the school year. From this page, the administrator will fill out the new modified school year dates, including:
										</p>
										<li>School year start/end date</li>
										<li>Fall semester start/end date</li>
										<li>Spring semester start/end date</li>
										<li>Quarter 1, 2, 3 start/end date</li>
										</br>
										<p>
											Once complete, the administrator will need to select the button “Modify school year”, and the new updates will be applied to the school year that the administrator selected to modify. 
										</p>
										<h4>4.3 - View All School Years</h4>
										<p>
											“View all school years” allows the administrator to view all current and previous school years for the Maplewood Baptist Academy. Displayed is a start and end date for the school year, fall semester, spring semester, and the three quarters of the year. 
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
										<h4>5.2 - Assign Teacher to Class</h4>
										<p>
											First select a grade level from the drop down list and click the "Select Grade Level" button. If there is not a class available for that grade level you will see the following message "No Classes for Grade Level, Select Another Class".  After you select the grade level you will select the class from the drop down list and click "Select Class".  Next you will select the teacher from the drop down list and click "Assign Teacher to Class".  You can also change the class or grade level at anytime by clicking the "Change Class" and "Change Grade Level" buttons accordingly.  
										</p>										
										<h4>5.3 - Assign Student to Class</h4>
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
										<h4>5.4 - Remove Student From Class</h4>
										<p style="color:red;">
											Please note that the student grades will be deleted for the class.
										</p>
										<p style="color:red;">		
                                            This will affect their GPA and transcript (if they have grades in that class)!
										</p>
										<p>
											First you will select the grade level from the drop down list and click the "Select Grade Level" button.  Then you will select the class from the drop down list and click the "Select Class" buton. Next select the student from the drop down list and click the "Remove Student from Clas" button. You also have the option to change the class and grade level by clicking the "Change Class" and "Change Grade Level" buttons accordingly.  
										</p>
										<h4>5.5 - Modify Class</h4>
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
										<h4>5.6 - Delete Class</h4>
										<p>
											The “Delete a Class” option, allows the administrator to delete any class that is no longer being taught or needed, at the Maplewood Baptist Academy. Simply select which class you would like to delete, via the Class Name drop box, and then select the button “Delete Class” to wipe the class from the SIS.   
										</p>
										<p style="color:red;">
											*If the class you are looking to delete isn’t available, you may need to change the grade level, via the button “Change Grade Level”. 
										</p>
										<h4>5.7 - View Student List by Class</h4>
										<p>
											“View Student List by Class”, allows the administrator to conveniently view every student that is registered to a class.  
										</p>
										<p>
											Select the grade level, and class name, to have a list displayed of all current students in the class. The list displays the first name, last name, and email for each student.  
										</p>
										<h4>5.8 - View All Classes</h4>
										<p>
											This option allows the administrator to view all classes offered at Maplewood Baptist Academy, regardless of grade level. It will display a list containing the class name, grade level, and the email for the teacher that is in charge of the class.  
										</p>
                                        </div>
                                    </div>
                                </div>					
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">6 - Announcements</a>
                                        </h4>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>6.1 - Create Announcement</h4>
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
										<h4>6.2 - Edit Announcement</h4>
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
										<h4>6.3 - Delete Announcement</h4>
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
										<h4>6.4 - View All Announcements</h4>
										<p>
											“View All announcements”, allows the administrator to view all announcements, whether expired or still viewable, that have been published on the student information system for Maplewood Baptist Academy. 
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
										<h4>7.2 - Generate Transcript Manually</h4>
										<p>
											This section allows you to manually generate a transcript from a CSV file.  You will fill out the following form fields: Student’s First Name, Student’s Last Name, Academic Year, Student’s GPA, and Student Grade Level.  Then you will upload a CSV file by clicking the “Browse…” button.  
										</p>
										<p>
											The format for the Manual Transcript CSV is: Class Name,Teacher Name,Q1,Q2,Q3,Q4  
										</p>
										<p>
											Then click the "Generate Manual Transcript" button to download the transcript.
										</p>										
										<p>
											<a href="https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba">To learn how to export a file from Excell as a CSV, click here</a>
										</p>										
										<h4>7.3 - Generate Report Card</h4>
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
										<h4>7.4 - Generate Report Card Manually</h4>
										<p>
											This section allows you to manually generate a report card from a CSV file.  You will fill out the following form fields: Student’s First Name, Student’s Last Name, Student’s GPA, and Student Grade Level.  Then you will upload a CSV file by clicking the “Browse…” button.  
										</p>
										<p>
											The format for the Manual Transcript CSV is: Class Name,Teacher Name,Q1,Q2,Q3,Q4  
										</p>
										<p>
											Then click the "Generate Manual Report Card" button to download the report card.
										</p>										
										<p>
											<a href="https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba">To learn how to export a file from Excell as a CSV, click here</a>
										</p>										
                                        </div>
                                    </div>
                                </div>	
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseEight">8 - Mass Emailing</a>
                                        </h4>
                                    </div>
                                    <div id="collapseEight" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>8.1 - Send a Mass Email</h4>
										<p>
											This allows you to send an email one of the following groups: Everyone, Teachers, Students, Parents, Elementary Parents and Students, High School Parents and Students, Elementary Teachers, High School Teachers.  Select your option from the drop down list and click “Choose Who to Email”.
										</p>
										<p>
											Then you will put your message in the "Email Body" section.  Once you have entered your message click the "Send Email" button.  You also have the option to change recipients by clicking the “Change Choice” button.
										</p>
                                        </div>
                                    </div>
                                </div>								
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseNine">9 - Profiles</a>
                                        </h4>
                                    </div>
                                    <div id="collapseNine" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>9.1 - My Profile</h4>
										<p>
											The “My Profile” page allows the current user to view their profile information. This information includes the First Name, Last Name, and Email of the user.
										</p>
										<h4>9.2 - Edit User Profile</h4>
										<p>
											The “Edit a Profile” page allows the administrator to edit a profile. The first thing the administrator is presented with, is the Role Type text box. From here, the administrator selects what kind of role the administrator would like to edit. So, if the administrator would like to edit a student’s profile, they would select “Role Type: Student”. This will bring up a list of all students, and from there, the administrator would select the user desired, and be allowed the option to edit the first name, last name, or email address.
										</p>
										<h4>9.3 - Lookup Student Profile</h4>
										<p>
											This allows the administrator to look up any profile to gather basic information on a user. Similar to the “Edit a Profile” page, the administrator will select which role the user has, and from there, a list of all users defined to that role type will be displayed, along with their information. 
										</p>
										<h4>9.4 - Edit Student Profile</h4>
										<p>
											This allows you to change students profile information.  Choose a student from the drop down list and click "Select User". Then you have the option to update the following fields including Student Email, Student First Name, Student Last Name, and Student Grade Level.  After you have made your updates click "Edit Student" button to save your changes.  You also have the option to change the student by clicking the "Change User" button.
										</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTen">10 - Data Export</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTen" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>10.1 - Export All Data (CSV)</h4>
										<p>
											This will include everything in the database, so this feature is meant only for programmers trying to reverse engineer the DB/Data.  Click the "Export All Data" button to process the download. 
										</p>
										<h4>10.2 - Export All Data (SQL)</h4>
										<p>
											This will include everything in the database, so this feature is meant only for programmers trying to reverse engineer the DB/Data.
										</p>
										<p>
											(Really this shouldn`t be too hard to re-import into a database after exported. We are using MariaDB if that helps you)
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
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1 - Dashboard </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
										<h4>1.1 - My Dashboard</h4>
										<p>
                                            The first thing the school administrator will see is the announcements widget, which gives you the Date, Name, and Descriptions of all announcements. Creation of these announcements will be explained further in the manual. 
										</p>		
										<p>
                                            Also shown are four panels that act as shortcuts to other parts of the application.  The function of these panels are explained later in the user manual. 
										</p>		
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2 - Classes</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>2.1 - View Student List by Class</h4>
										<p>
											“View Student List by Class”, allows the school administrator to conveniently view every student that is registered to a class.  
										</p>
										<p>
											Select the grade level, and class name, to have a list displayed of all current students in the class. The list displays the first name, last name, and email for each student.  
										</p
										</br>
										<h4>2.2 - View All Classes</h4>
										<p>
											This option allows the school administrator to view all classes offered at Maplewood Baptist Academy, regardless of grade level. It will display a list containing the class name, grade level, and the email for the teacher that is in charge of the class.  
										</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3 - Announcement</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>3.1 - Create Announcement</h4>
										<p>
											The first option given is “create an announcement”.  The school administrator will be given 4 text boxes to input information.
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
										<h4>3.2 - Edit Announcement</h4>
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
										<h4>3.3 - Delete Announcement</h4>
										<p>
											The delete announcement tab, allows the Administrator to delete any announcements that have been created. This applies for both expired announcements, and current announcements still viewable.  
										</p>
										<p>
											Under “Announcement Title, Announcement Post Date”, select the announcement you wish to delete, then click the button “Delete Announcement”.  	 
										</p>
										<p style="color:red;">	
											*Warning* once you delete an announcement, you will be unable to retrieve it later.   
										</p>
										<h4>3.4 - View All Announcements</h4>
										<p>
											“View All announcements”, allows the administrator to view all announcements, whether expired or still viewable, that have been published on the student information system for Maplewood Baptist Academy. 
										</p>
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">4 - Report Generation</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        <h4>4.1 - Generate Transcript</h4>
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
										<h4>4.2 - Generate Transcript Manually</h4>
										<p>
											This section allows you to manually generate a transcript from a CSV file.  You will fill out the following form fields: Student’s First Name, Student’s Last Name, Academic Year, Student’s GPA, and Student Grade Level.  Then you will upload a CSV file by clicking the “Browse…” button.  
										</p>
										<p>
											The format for the Manual Transcript CSV is: Class Name,Teacher Name,Q1,Q2,Q3,Q4  
										</p>
										<p>
											Then click the "Generate Manual Transcript" button to download the transcript.
										</p>										
										<p>
											<a href="https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba">To learn how to export a file from Excell as a CSV, click here</a>
										</p>										
										<h4>4.3 - Generate Report Card</h4>
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
										<h4>4.4 - Generate Report Card Manually</h4>
										<p>
											This section allows you to manually generate a report card from a CSV file.  You will fill out the following form fields: Student’s First Name, Student’s Last Name, Student’s GPA, and Student Grade Level.  Then you will upload a CSV file by clicking the “Browse…” button.  
										</p>
										<p>
											The format for the Manual Transcript CSV is: Class Name,Teacher Name,Q1,Q2,Q3,Q4  
										</p>
										<p>
											Then click the "Generate Manual Report Card" button to download the report card.
										</p>										
										<p>
											<a href="https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba">To learn how to export a file from Excell as a CSV, click here</a>
										</p>										
                                        </div>
                                    </div>
                                </div>	
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">5 - Mass Emailing</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>5.1 - Send a Mass Email</h4>
										<p>
											This allows you to send an email one of the following groups: Everyone, Teachers, Students, Parents, Elementary Parents and Students, High School Parents and Students, Elementary Teachers, High School Teachers.  Select your option from the drop down list and click “Choose Who to Email”.
										</p>
										<p>
											Then you will put your message in the "Email Body" section.  Once you have entered your message click the "Send Email" button.  You also have the option to change recipients by clicking the “Change Choice” button.
										</p>
                                        </div>
                                    </div>
                                </div>									
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">6 - Profiles</a>
                                        </h4>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>6.1 - My Profile</h4>
										<p>
											The “My Profile” page allows the current user to view their profile information. This information includes the First Name, Last Name, and Email of the user.
										</p>
										<h4>6.2 - Lookup Student Profile</h4>
										<p>
											This allows you to look up any student profile of current students.  Choose a student from the drop down list and click "Select User".  Then you will see the following information: Student Email, Student First Name, Student Last Name, and Student Grade Level.  Below that you will see the students current GPA, Cummlative GPA, and a list of their current classes along with the corresponding grade.  You also have the option to change the student by clicking the "Change User" button.   
										</p>
										<h4>6.3 - Edit Student Profile</h4>
										<p>
											This allows you to change students profile information.  Choose a student from the drop down list and click "Select User". Then you have the option to update the following fields including Student Email, Student First Name, Student Last Name, and Student Grade Level.  After you have made your updates click "Edit Student" button to save your changes.  You also have the option to change the student by clicking the "Change User" button.
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
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1 - Dashboard </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
										<h4>1.1 - My Dashboard</h4>
										<p>
                                            The first thing the you will see is the announcements widget, which gives you the Date, Name, and Descriptions of all announcements.  
										</p>
										<p>
                                            Also shown are three panels that act as shortcuts to other parts of the application.  The function of these panels are explained later in the user manual. 
										</p>										
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2 - Profiles</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>2.1 - My Profile</h4>
										<p>
											The “My Profile” page allows the current user to view their profile information. This information includes the First Name, Last Name, and Email of the user.
										</p>
										<h4>2.2 - Lookup Student Profile</h4>
										<p>
											This allows the administrator to look up any profile to gather basic information on a user. Similar to the “Edit a Profile” page, the administrator will select which role the user has, and from there, a list of all users defined to that role type will be displayed, along with their information. 
										</p>
										<h4>2.3 - Edit Student Profile</h4>
										<p>
											This allows you to change students profile information.  Choose a student from the drop down list and click "Select User". Then you have the option to update the following fields including Student Email, Student First Name, Student Last Name, and Student Grade Level.  After you have made your updates click "Edit Student" button to save your changes.  You also have the option to change the student by clicking the "Change User" button.
										</p>
                                        </div>
                                    </div>
                                </div>								
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3 - Data Export</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>3.1 - Export Students Grades</h4>
										<p>
											This allows you to export your students grade by class in a CSV file.  First you will select the class from the drop down list and click "Select Class".  To download the file click "Export Student Grades".  The top of the file will include Class Name, Students Name, and Current Grade.  Below that will list out each assignment including: Assignment Name, Assignment Due Date, Assignment Type, Points Scored, Points Possible, and Assignment Grade. You also have the option to change the class by clicking the "Change Class" button.
										</p>
                                        </div>
                                    </div>
                                </div>								
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">4 - My Classes/Students</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>4.1 - View Student List</h4>
										<p>
											The View Student List section shows a list of all your students in each of your classes.  The class name is located in the top left corner of the gray table.  Each class shows a list that includes first name, last name, email, and current grade.
										</p>
										<h4>4.2 - View Grades for Student</h4>
										<p>
											The View Grades for Student section allows you to view a student`s grade based on one of their classes.  The system will default to one of your classes which you can see in the gray table.  If you would like to change the class you can [Click] on the “change class” button. Then select a class from the dropdown list and [Click] the “select class” button.  After you have your class selected you will choose a student from the dropdown list and [Click] the “Select Student” button.  At the top of the table you will see the class name, student name, and their current grade.  Inside of the table you will see a detailed list of the students assignments including the assignment name, due date, type, points scored, points possible, and percentage grade as shown below.  You can change the student or class at anytime by clicking on either the “Change Student” or “Change Class” button and following the  instructions above.
										</p>
										<h4>4.3 - Gradebook</h4>
										<p>
											The Gradebook section allows you to manage your assignment grades for each of your classes.  The system will default to one of your classes which you can see in the gray table.  If you would like to change the class you can [Click] on the “change class” button. Then select a class from the dropdown list and [Click] the “select class” button.  After you have your class selected choose an assignment from the dropdown list and [Click] the “Select Assignment” button.  At the top of the table you will see the class name along with the assignment name.  Inside of the table you will see list of students for the assignment including the student first name, last name, points earned, points possible, and an apply changes button.  You will be able to update and change the score, at any time, for each student by updating the “Points Earned” field and [Clicking] the “Apply Changes” button.  You will see a “Grade Change” message in the gray table once this has updated.  You will only be able to update one student score at a time.  You can change the assignment or class at anytime by clicking on either the “Select Assignment” or “Change Class” button and follow the instructions above.  
										</p>
										<h4>4.4 - Assign Student to Class</h4>
										<p>
											The Assign Student to Class section allows you to assign a student to any of you classes. First you will select the grade level from the dropdown list and [Click] the “Select Grade Level” button. If you select a grade level and you dont have classes assigned you see a “No Classes for Grade Level, Select Another Class” message.  Then you will select a class from the dropdown list and [Click] the “Select Class” button. You could also change the grade level by clicking on the “Change Grade Level” button.  At the top of the table you will see the grade level along with the class name.  Then you will choose a student from the dropdown list and [Click] “Add Student to Class” button. You will see a “Student added to Class” message in the gray table if successful.  If the drop down list says  “No Students” then the student is already enrolled in the class.  You can change the grade level or class at anytime by clicking on either the “Change Class” or “Change Grade Level” button and follow the instructions above.
										</p>
										<h4>4.5 - Remove Student from Class</h4>
										<p>
											The Remove Student From Class section allows you to remove a student from any of you classes. First you will select the grade level from the dropdown list and [Click] the “Select Grade Level” button. If you select a grade level and you dont have classes assigned you see a “No Classes for Grade Level, Select Another Class” message.  Then you will select a class from the dropdown list and [Click] the “Select Class” button. You could also change the grade level by clicking on the “Change Grade Level” button.  At the top of the table you will see the grade level along with the class name.  Then you will choose a student from the dropdown list and [Click] “Remove Student from Class” button. You will see a “Student removed from Class” message in the gray table if successful.  If the drop down list says  “No Students” then the student is already enrolled in the class.  You can change the grade level or class at anytime by clicking on either the “Change Class” or “Change Grade Level” button and follow the instructions above.
										</p>
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">5 - Assignments</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>5.1 - Add Assignment</h4>
										<p>
											The Add Assignment section allows you to add a new assignment to any of your classes.  First you will select the class from the dropdown list and [Click] the “Select Class” button.  You will have four fields to fill out in order to save a new assignment.  They include Assignment Name, Assignment Points Possible, Assignment Due Date, and Type of assignment. Then you [Click] the “Add Assignment” button to save your new assignment.  You will see a “Assignment Added” message in the gray table if successful.  You must have a “Type of Assignment” created in order to save a new assignment.  You can change the class at anytime by clicking the “Change Class” button.  In order to add a new “Assignment Type” please refer to section 4.5.1. 
										</p>
										<h4>5.2 - Modify Assignment</h4>
										<p>
											The View Grades for Student section allows you to view a student`s grade based on one of their classes.  The system will default to one of your classes which you can see in the gray table.  If you would like to change the class you can [Click] on the “change class” button. Then select a class from the dropdown list and [Click] the “select class” button.  After you have your class selected you will choose a student from the dropdown list and [Click] the “Select Student” button.  At the top of the table you will see the class name, student name, and their current grade.  Inside of the table you will see a detailed list of the students assignments including the assignment name, due date, type, points scored, points possible, and percentage grade as shown below.  You can change the student or class at anytime by clicking on either the “Change Student” or “Change Class” button and following the  instructions above.
										</p>
										<h4>5.3 - Delete Assignment</h4>
										<p>
											The Delete Assignment section allows you to delete an existing assignment to any of your classes.  First you will select the class from the dropdown list and [Click] the “Select Class” button.  Then you will select the assignment from the dropdown list and [Click} the “Delete Assignment” button.  You will see a “Assignment Deleted” message in the gray table if successful.  You can change the class at anytime by clicking on the “Change Class”  button and follow the instructions above. 
										</p>
										<h4>5.4 - View All Assignments</h4>
										<p>
											The View All Assignment section shows a list of all your assignments in each of your classes.  The class name is located in the top left corner of the gray table.  Each class shows a list that includes Assignment Name, Assignment Points Possible, Assignment Due Date, and Assignment Type.  In order to modify an assignment please refer to section 4.4.2
										</p>
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">6 - Class Configuration</a>
                                        </h4>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        <h4>6.1 - Add Assignment Type</h4>
										<p>
											This section allows you to add all of your grading material types.  These could include Test, Exams, Quiz, Homework, etc.  This application uses a weighted scoring system.  To add a material type you will first select your class from the drop down menu and click "Select Class".  Then you will fill in the "Assignment Type Name" field and the "Assignment Type Weight" feild.  Once these have been filled in you can click the "Add Material Type" button.  You can change the class at anytime by clicking on the "Change Class" button.
										</p>
										<h4>6.2 - Modify assignment Type</h4>
										<p>
											This section allows you to modify an assignment type previously created.  First you will select your class from the drop down list and click "Select Class".  You will then select the material type to modify from the drop down list and click "Select Assignment Type".  The next screen allows you to modify the "Assignment Type Name" and the "Assignment Type Weight".  Once these have been updated you click on the "Modify Material Type" button.  You also have the option to change your class by clicking on the "Change Class" button or assignment type by clicking on the "Change Assignment Type" button.
										</p>
										<p>
											You also have the option to change your class by clicking on the "Change Class" button or assignment type by clicking on the "Change Assignment Type" button.
										</p>
										<h4>6.3 - Delete Assignment Type</h4>
										<p>
											This section allows you to delete an assignment type. First you will choose your class from the drop down list and click "Select Class".  Then you will choose the assignment type from the drop down list.  To delete the assignmnet type click the "Delete Assignment Type" button.  You can change the class by clicking the "Change Class" button.
										</p>
										<p style="color:red;">
											*This will be permanently deleted
										</p>
										<h4>6.4 - View All Assignment Types</h4>
										<p>
											This section allows you to view all your assignment types for all of your classes.  Each table includes the class name along with assignment type and assignment weight.  
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
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1 - Dashboard</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <h4>1.1 - My Dashboard</h4>
										<p>
                                            The first thing you will see is the announcements widget, which gives you the Date, Name, and Descriptions of all announcements. 
										</p>
										<p>
                                            Also shown are three panels that act as shortcuts to other parts of the application.  The function of these panels are explained later in the user manual.  
										</p>
										
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2 - Profiles</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        <h4>2.1 - My Profile</h4>
										<p>
                                            The “My Profile” page allows you to view your profile information. This information includes your First Name, Last Name, and Email.
										</p>
                                        </div>
                                    </div>
                                </div>								
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3 - Student</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        <h4>3.1 - View All Class Grades</h4>
										<p>
                                            This section shows you infomration about your childrens classes and grades.  For each child, you will see a table that include Student Name, Current GPA, Cumulative GPA, and a list ofIt will give you tables of all the students that you have the access to.  At the top of each table you will see the students current GPA, Cummlative GPA, and a list of their current classes along with the corresponding grade.
										</p>
										<h4>3.2 - View All Student Assignments</h4>
										<p>
                                            This section allows you to view all assignments for each of your childs classes.  First you will select your child from the drop down list and click "Select Student".  For each of your childs classes you will see the following infomration: Assignment Name, Assignment Due Date, Assignment Type, Points Scored, Points Possible, and Assignment Grade.  To change children click the "Change Student" button at the bottom of the page. (This only applies to parents with more then one child enrolled)
										</p>
										<h4>3.3 - Contact Teacher/Principal</h4>
										<p>
											This allows you to send an email to the principal or one of your childs teachers.  For emailing a teacher you will select "Email Teacher" from the dropdown list and click "Choose Option" button.  Then you will select the appropriate teacher from the drop down list and click the "Choose Teacher" button.  For emailing the principal you will select "Email Principal" from the dropdown list.
										</p>
										<p>
											Then you will put your message in the "Email Body" section.  Once you have entered your message click the "Send Email" button.  You also have the option to change recipients by clicking the “Change Choice” button.
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
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1 - Dashboard</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <h4>1.1 - My Dashboard</h4>
										<p>
                                            The first thing you will see is the announcements widget, which gives you the Date, Name, and Descriptions of all announcements. 
										</p>
										<p>
                                            Below the announcements is your current class schedule including Class Name, Teacher, Start Time, and End Time.
										</p>
										<p>
                                            Also shown are three panels that act as shortcuts to other parts of the application.  The function of these panels are explained later in the user manual.  
										</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2 - Profiles</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        <h4>2.1 - My Profile</h4>
										<p>
                                            The “My Profile” page allows you to view your profile information. This information includes your First Name, Last Name, Email, Student ID, Grade Level, DOB, and Gender.
										</p>
										<p>
                                            Below your profile is your current class schedule including Class Name, Teacher, Start Time, and End Time. 
										</p>
										<p>
                                            The last section shows your parent contact information including First Name, Last Name, Email, Address, and Phone Number. 
										</p>
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3 - Grades</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>3.1 - View All Class Grades</h4>
										<p>
											This screen will show you your Current GPA and Cumulative GPA in the top left corner of the table.  Inside the table you will see all your classes and the grades for the current semester.
										</p>
										<h4>3.2 - Conact Teacher/Principal</h4>
										<p>
											This allows you to send an email to the principal or one of your teachers.  For emailing a teacher you will select "Email Teacher" from the dropdown list and click "Choose Option" button.  Then you will select the appropriate teacher from the drop down list and click the "Choose Teacher" button.  For emailing the principal you will select "Email Principal" from the dropdown list.
										</p>
										<p>
											Then you will put your message in the "Email Body" section.  Once you have entered your message click the "Send Email" button.  You also have the option to change recipients by clicking the “Change Choice” button.
										</p>									
                                        </div>
                                    </div>
                                </div>
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">4 - Assignments</a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse">
                                        <div class="panel-body">
										<h4>4.1 - View Due Assignments</h4>
										<p>
											You will see all the classes and all the assignments with specific details ( Assignment Name, Assignment Type, Assignment Due Date and Assignment Points Possible). 
										</p>
										<h4>4.2 - View All Assignments</h4>
										<p>
											Will give you an overall assignments the past ones and the current one by having that you will have a good idea on every single assignment. 
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
echo '

                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

        </body>

        </html>
    ';
?>

	