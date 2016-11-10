<?php
include_once '../includes/register.inc.php';
include_once '../includes/functions.php';
include_once '../includes/dbConnect.php';

sec_session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>openEasySIS - Create Account</title>

    <!-- Header Information, CSS, and JS -->
    <?php include("../includes/header.php"); ?>
    <script type="text/JavaScript" src="../js/sha512.js"></script> 
    <script type="text/JavaScript" src="../js/forms.js"></script> 
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Sign in to openEasySIS</h3>
                    </div>
                    <div class="panel-body">
                        <!--<form role="form">-->
			<form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post" 
 			role="form" name="registration_form">
			<fieldset>
				<div class="form-group">
                                     <input class='form-control' placeholder="Username" name='username' id='username' autofocus>
				</div>

				<div class="form-group">
                                     <input class='form-control' placeholder="Email" name='email' id='email' type="email" >
                                </div>
				<div class="form-group">
                                     <input class='form-control' placeholder="Password" name='password' id='password' type="password" value="">
                                </div>

				<div class="form-group">
                                     <input class='form-control' placeholder="Confirm Password" name='confirmpwd' id='confirmpwd' type="password" value="">
                                </div>
                                        <input type="button" 
					       class="btn btn-lg btn-success btn-block"
                                               value="Create Account" 
                                               onclick="return regformhash(this.form,
                                                               this.form.username,
                                                               this.form.email,
                                                               this.form.password,
                                                               this.form.confirmpwd);" />
			</fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
