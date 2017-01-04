<?php




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
                                <div class="tab-pane fade in active" id="administrator">
                                    <h4>Create Announcement</h4>
                                    <form action="../includes/adminFunctions/createAnnouncement" method="post" role="form">
                                        <div class="form-group">
                                            <input class="form-control" name="announcementTitle" placeholder="Announcement Title">
                                        </div>
                                        <div class="form-group">
											<label>Announcement Post Date</label>
											<input class="form-control" type="date" name="announcementPostDate" value="' . date('Y-m-d') . '" min="' . date('Y-m-d') . '">
                                        </div>
                                        <div class="form-group">
											<label>Announcement End Date</label>
											<input class="form-control" type="date" name="announcementEndDate" min="' . date('Y-m-d') . '">
                                        </div>
										<div class="form-group">
                                            <label>Announcement Description</label>
                                            <textarea class="form-control" name="announcementDescription" rows="5"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-default">Create Announcement</button>
                                    </form>
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
