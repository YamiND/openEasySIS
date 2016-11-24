<!DOCTYPE html>

<?php
include_once '../includes/dbConnect.php';
include_once '../includes/functions.php';

sec_session_start();
if (!isset($_SESSION['roleID'], $_SESSION['userID'], $_SESSION['userEmail'])):

?>
<html lang="en">

<head>
    <title><?php echo aliasOpenEasySIS; ?> - Login</title>

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
    						echo '<h3 class="panel-title">' . $_SESSION['invalidLogin'] . '</h3>';
    						unset($_SESSION['invalidLogin']);
						}
						else
						{
                        	echo '<h3 class="panel-title">Sign in to ' . aliasOpenEasySIS . '</h3>';
						}
					?>
                    </div>
                    <div class="panel-body">
                        <!--<form role="form">-->
			<form action="../includes/processLogin" method="post" name="login_form" role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="userEmail" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
				
				<div class="form-group">
				    <a href="forgotPassword">Forgot your password?</a>
				</div>
				<!-- I may implement a remember me feature in the future -->
                              <!--  <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>-->
                                <!-- Change this to a button or input when using this as a form -->
<!--                                <a href="index.html" class="btn btn-lg btn-success btn-block">Login</a> -->
				<input type="Submit" class="btn btn-lg btn-success btn-block" 
                                                   value="Sign in" />
			<!--	<input type="button" class="btn btn-lg btn-success btn-block" 
                                                   value="Sign in" 
                                                   onclick="formhash(this.form, this.form.password);" />-->
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php

else:
//TODO: Update this with a better page
$url = "index";
header("Location:$url");
return;
endif;
?>
