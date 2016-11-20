<?php

function viewUserPasswordResetForm($mysqli)
{
    echo ' <!-- /.col-lg-4 -->
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
						';  
                        if (isset($_SESSION['invalidReset']))
                        {   
                            echo $_SESSION['invalidReset'];
                            unset($_SESSION['invalidReset']);
                        }   
                        else if (isset($_SESSION['resetSuccess']))
						{
                            echo $_SESSION['resetSuccess'];
                            unset($_SESSION['resetSuccess']);
						}
                        else
                        {   
                        	echo 'Reset Password';
                        }   
	echo '  
                        </div>
                        <div class="panel-body">
            <form action="../includes/userFunctions/userPasswordReset" method="post" name="login_form" role="form">
            <input type="hidden" name="userEmail" value="' . $_SESSION["userEmail"] . '"> 
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Enter old Password" name="oldPassword" type="password" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Enter new Password" name="newPassword" type="password" value="">
                                </div>
                <div class="form-group">
                                    <input class="form-control" placeholder="Repeat new Password" name="repeatPassword" type="password" value="">
                                </div>

                                <!-- I may implement a remember me feature in the future -->
                              <!--  <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me                                    </label>
                                </div>-->
                                <!-- Change this to a button or input when using this as a form -->
<!--                                <a href="index.html" class="btn btn-lg btn-success btn-block">Login</a> -->
                                <input type="Submit" class="btn btn-lg btn-success btn-block" 
                                                   value="Reset Password" />
                            </fieldset>
                        </form> 
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-4 -->';
}
?>
