<!DOCTYPE html>

<?php
include_once '../includes/dbConnect.php';
include_once '../includes/functions.php';
include_once '../includes/userFunctions/forgotPassword.php';

sec_session_start();
?>
<html lang="en">

<head>
    <title>openEasySIS - Forgot Password?</title>

    <!-- Header Information, CSS, and JS -->
    <?php include("../includes/header.php"); ?>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
					<?php
						if (isset($_SESSION['invalidLogin']))
						{
    						echo '<h3 class="panel-title">' . $_SESSION['invalidEmail'] . '</h3>';
    						unset($_SESSION['invalidEmail']);
						}
						else
						{
                        	echo '<h3 class="panel-title">Enter your email</h3>';
						}
					?>
                    </div>
                    <div class="panel-body">
                        <!--<form role="form">-->
			<form action="../includes/userFunctions/forgotPassword" method="post" name="login_form" role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="userEmail" type="email" autofocus>
                                </div>
				<input type="Submit" class="btn btn-lg btn-success btn-block" 
                                                   value="Reset Password" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
