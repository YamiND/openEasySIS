<?php
include_once '../includes/dbConnect.php';
include_once '../includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true):

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
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
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
';

if (roleID_check($mysqli) == 1)
{
echo '
                        <li>
                            <a href="#"><i class="fa fa-dashboard fa-fw"></i> Dashboard <span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a href="adminDashboard">Overview</a>
								</li>
								<li>
									<a href="createUserAccount">Create User</a>
								</li>
								<li>
									<a href="adminPasswordReset">Reset User Password</a>
								</li>
								<li>
									<a href="viewUserTables">View Users</a>
								</li>
								<li>
									<a href="viewClassesTable">View Classes</a>
								</li>
							</ul>	
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Charts<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="flot">Flot Charts</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="tables"><i class="fa fa-table fa-fw"></i> Tables</a>
                        </li>
                        <li>
                            <a href="forms"><i class="fa fa-edit fa-fw"></i> Forms</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panels-wells">Panels and Wells</a>
                                </li>
                                <li>
                                    <a href="buttons">Buttons</a>
                                </li>
                                <li>
                                    <a href="typography">Typography</a>
                                </li>
                                <li>
                                    <a href="icons"> Icons</a>
                                </li>
                                <li>
                                    <a href="grid">Grid</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="blank">Blank Page</a>
                                </li>
                                <li>
                                    <a href="login">Login Page</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
';
}
else if (roleID_check($mysqli) == 2)
{
echo '
                        <li>
                            <a href="schoolAdminDashboard"><i class="fa fa-dashboard fa-fw"></i> School Administrator</a>
                        </li>
';
}
else if (roleID_check($mysqli) == 3)
{
	echo '
                        <li>
                            <a href="teacherDashboard"><i class="fa fa-dashboard fa-fw"></i> Teacher</a>
                        </li>
	';
}
else if (roleID_check($mysqli) == 4)
{
	echo '
                        <li>
                            <a href="guardianDashboard"><i class="fa fa-dashboard fa-fw"></i> Parent/Guardian</a>
                        </li>
	';
}
else if (roleID_check($mysqli) == 5)
{
echo '
                        <li>
                            <a href="studentDashboard"><i class="fa fa-dashboard fa-fw"></i>Student</a>
                        </li>
';
}
else
{
	//TODO: Redirect to error page or some sort of logging
	echo "Invalid Role ID";
	echo "<br>";
}

	echo '
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
	';

    else:
   	$url = "login"; 
	header("Location:$url");
	return;
    endif;
?>
