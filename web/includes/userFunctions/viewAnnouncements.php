<?php
include_once '../dbConnect.php';


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
                                        <th>Announcement Title</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    if ($stmt = $mysqli->prepare("SELECT announcementDate, announcementTitle, announcementDescription FROM announcements"))
                                    {   
                                        $stmt->execute();
                                        $stmt->bind_result($announcementDate, $announcementTitle, $announcementDescription);
                                        $stmt->store_result();

                                        while($stmt->fetch())
                                        {   
                                            echo "<tr>";
                                            echo "<td>" . $announcementDate . "</td>";
                                            echo "<td>" . $announcementTitle . "</td>";
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
