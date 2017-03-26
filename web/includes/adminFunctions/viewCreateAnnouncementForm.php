<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli) || isSchoolAdmin($mysqli)))
    {
        viewCreateAnnouncementForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewCreateAnnouncementForm($mysqli)
{
	//This is required otherwise it defaults to UTC I think
	date_default_timezone_set('America/New_York');

	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("Create Announcement");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#createAnnoucement" data-toggle="tab">Create Announcement</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="announcement">
                                <br>
            ';

                                generateFormStart("../includes/adminFunctions/createAnnouncement", "post");
                                    generateFormInputDiv("Announcement Name", "text", "announcementName", NULL, NULL, NULL, NULL, "Announcement Name");
                                    generateFormInputDiv("Announcement Post Date", "date", "announcementPostDate", date('Y-m-d'), NULL, date('Y-m-d'));
                                    generateFormInputDiv("Announcement End Date", "date", "announcementEndDate", NULL, NULL, date('Y-m-d'));
                                    generateFormTextAreaDiv("Announcement Description", "announcementDescription", "5");
									generateFormCheckboxDiv(NULL, "sendAllUsers", NULL, "Send Email to all Users");
									generateFormCheckboxDiv(NULL, "sendAllElementary", NULL, "Send Email to Elementary Students and Parents");
									generateFormCheckboxDiv(NULL, "sendAllHighschool", NULL, "Send Email to High School Students and Parents");
                                    generateFormButton("createAnnouncementButton", "Create Announcement");
                                generateFormEnd();

                                    
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

?>
