<?php

date_default_timezone_set('America/New_York');

function viewAnnouncements($mysqli)
{
    echo '
            <!-- col-lg-12 -->
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Announcements
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date Posted</th>
                                        <th>Announcement Name</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
        ';
                                    if ($stmt = $mysqli->prepare("SELECT announcementPostDate, announcementEndDate, announcementName, announcementDescription FROM announcements WHERE announcementEndDate >= curdate() AND announcementPostDate <= curdate()"))
                                    {   
                                        $stmt->execute();
                                        $stmt->bind_result($announcementPostDate, $announcementEndDate, $announcementName, $announcementDescription);
                                        $stmt->store_result();

                                        while($stmt->fetch())
                                        {  
                                        	echo "<tr>";
                                        	echo "<td>" . $announcementPostDate . "</td>";
                                        	echo "<td>" . $announcementName . "</td>";
                                        	echo "<td>" . $announcementDescription . "</td>";
                                        	echo "</tr>";
                                        }   
                                    }   
                                    else
                                    {   
                                        return;
                                    }   
        echo '
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            ';
}

?>
