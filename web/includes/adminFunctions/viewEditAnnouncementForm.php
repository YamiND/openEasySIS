<?php

date_default_timezone_set('America/New_York');

function viewEditAnnouncementForm($mysqli)
{
	//This is required otherwise it defaults to UTC I think
	date_default_timezone_set('America/New_York');
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						if (isset($_SESSION['invalidUpdate']))
                        {
                        	echo $_SESSION['invalidUpdate'];
                            unset($_SESSION['invalidUpdate']);
                        }
						else if (isset($_SESSION['invalidSelect']))
						{
							echo $_SESSION['invalidSelect'];
							unset($_SESSION['invalidSelect']);
						}
						else if (isset($_SESSION['announcementSelected']))
						{
							echo $_SESSION['announcementSelected'];
						}
						else if (isset($_SESSION['updateSuccess']))
						{
                        	echo $_SESSION['updateSuccess'];
                            unset($_SESSION['updateSuccess']);
						}
                        else
                        {
                        	echo 'Update Announcement';
                        }
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#updateAnnoucement" data-toggle="tab">Update Announcement</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                            	<h4>Select Announcement</h4>
                                <div class="tab-pane fade in active" id="updateAnnouncement">

                                    <form action="../includes/adminFunctions/selectAnnouncement" method="post" role="form">';

										if (isset($_SESSION['announcementSelected'], $_SESSION['announcementID'], $_SESSION['announcementTitle'], $_SESSION['announcementPostDate']))
										{
											$announcementID = $_SESSION['announcementID'];
											$announcementTitle = $_SESSION['announcementTitle'];
											$announcementPostDate = $_SESSION['announcementPostDate'];
										
											echo '<fieldset disabled>
													<div class="form-group">
														<select class="form-control" name="announcementID">
															<option value="' . $announcementID . '">' . $announcementTitle . ', ' . $announcementPostDate . '</option>
														</select>
													</div>
													<button type="submit" class="btn btn-default">Select Announcement</button>
													</fieldset>';

										}
										else
										{
											echo ' 
                                        			<div class="form-group">
			                                        	<select class="form-control" name="announcementID">';
															getAnnouncements($mysqli);
				echo '									</select> 
           				                             </div>
                        			                <button type="submit" class="btn btn-default">Select Announcement</button>';
										}

echo '
                                    </form>
								
									<br>
	
                                    <form action="../includes/adminFunctions/updateAnnouncement" method="post" role="form">';
				
											if (isset($_SESSION['announcementID'], $_SESSION['announcementTitle'], $_SESSION['announcementPostDate'], $_SESSION['announcementSelected'], $_SESSION['announcementDescription']))
											
                        					{
					                        	echo '
														<input type="hidden" name="announcementID" value="'.$announcementID.'">
														<div class="form-group">
															<input class="form-control" name="announcementTitle" value="' . $_SESSION['announcementTitle'] . '">
														</div>
														<div class="form-group">
															<label>Announcement Post Date</label>
															<input class="form-control" type="date" name="announcementPostDate" value="' . $_SESSION['announcementPostDate'] . '">
														</div>
														<div class="form-group">
															<label>Announcement End Date</label>
															<input class="form-control" type="date" name="announcementEndDate" value="' . $_SESSION['announcementEndDate'] . '">
														</div>
														<div class="form-group">
															<label>Announcement Description</label>
															<textarea class="form-control" name="announcementDescription" rows="5">' . $_SESSION['announcementDescription'] . '</textarea>
														</div>
														<button type="submit" class="btn btn-default">Update Announcement</button>
													';

												unset($_SESSION['announcementSelected']);
												unset($_SESSION['announcementID']);
												unset($_SESSION['announcementTitle']);
												unset($_SESSION['announcementPostDate']);
                        					}
											else
											{
												echo '
												<fieldset disabled>
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
													<button type="submit" class="btn btn-default">Update Announcement</button>
												</fieldset>';
											}
	echo '
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

function getAnnouncements($mysqli)
{

	if ($stmt = $mysqli->prepare("SELECT announcementID, announcementPostDate, announcementEndDate, announcementTitle FROM announcements"))
    {   
    	$stmt->execute();
        $stmt->bind_result($announcementID, $announcementPostDate, $announcementEndDate, $announcementTitle);
        $stmt->store_result();

        while($stmt->fetch())
        { 
			if ((($announcementEndDate >= date('Y-m-d')) || ($announcementEndDate === NULL) || ($announcementEndDate == '0000-00-00')) && ($announcementPostDate <= date('Y-m-d')))
			{
				echo "<option value='" . $announcementID . "'>$announcementTitle, $announcementPostDate</option>";
			}
        }   
    }   
    else
    {   
    	return;
    }   
}

?>
