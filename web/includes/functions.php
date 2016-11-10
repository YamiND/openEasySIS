<?php
include_once 'dbConfig.php';
 
function sec_session_start() 
{
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    session_name($session_name);
 
    // This stops JavaScript being able to access the session id.
    //$secure = SECURE;
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../pages/error?err=Could not initiate a safe session (ini_set)"); // TODO: We need to fix the error page
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    //session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id(true);    // regenerated the session, delete the old one. 
}

function login($userEmail, $password, $mysqli) 
{
	if (DEBUG):
  		echo "In login function \n";
	endif;

    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT userID, userPassword, roleID, userSalt 
        FROM users WHERE userEmail = ? LIMIT 1")) 
    {
        $stmt->bind_param('s', $userEmail);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($userID, $dbPassword, $roleID, $userSalt);
        $stmt->fetch();
	
	$tempPassword = $password;  // This value is the hashed passwodr passed in
        // hash the password with the unique salt.
        $password = hash('sha512', $password . $userSalt);
	
		if (DEBUG):
        	echo "User ID: " . $userID . "<br>";
			echo "Role ID: " . $roleID . "<br>";
            echo "DB Password: " . $dbPassword . "<br>";
			echo "Salt: " . $userSalt . "<br>";
            echo "Entered Password: " . $tempPassword . "<br>";
            echo "Hashed Entered Password: " . $password . "<br>";
        endif;

        if ($stmt->num_rows == 1) 
		{
            // If the user exists we check if the account is locked
            // from too many login attempts 

  /*          if (checkbrute($userID, $mysqli) == true) 
            {
                // Account is locked 
                // Send an email to user saying their account is locked
                return false;
            } 
            else 
            {*/
                // Check if the password in the database matches
                // the password the user submitted.
                if ($dbPassword == $password) 
                {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $userID = preg_replace("/[^0-9]+/", "", $userID);
                    $_SESSION['userID'] = $userID;
					
                    $roleID = preg_replace("/[^0-9]+/", "", $roleID);
                    $_SESSION['roleID'] = $roleID;
                    // XSS protection as we might print this value
                    //$username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                //"", 
                                                                //$username);
                    //$_SESSION['username'] = $username;
		    		$_SESSION['userEmail'] = $userEmail;
                    $_SESSION['login_string'] = hash('sha512', 
                              $dbPassword . $user_browser);
		
			// all session variables are set at this point	
                    // Login successful.
                    return true;
                } 
                else 
                {
                    // Password is not correct
                    // We record this attempt in the database
                //    $now = time();
                 //   $mysqli->query("INSERT INTO loginAttempts(userID, time)
                  //                  VALUES ('$userID', '$now')");
                    return false;
                } 
           // }
        } 
        else 
        {
            // No user exists.
            return false;
        }
    }
}

/*function checkbrute($userID, $mysqli) 
{
    // Get timestamp of current time 
    $now = time();
 
    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);
 
    if ($stmt = $mysqli->prepare("SELECT time 
                             FROM loginAttempts 
                             WHERE userID = ? 
                            AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $userID);
 
        // Execute the prepared query. 
        $stmt->execute();
        $stmt->store_result();
 
        // If there have been more than 5 failed logins 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}*/

function login_check($mysqli) 
{
    // Check if all session variables are set 
    if (isset($_SESSION['userID'], $_SESSION['userEmail'], $_SESSION['roleID'], $_SESSION['login_string'])) 
    {
        $userID = $_SESSION['userID'];
        $login_string = $_SESSION['login_string'];
		$userEmail = $_SESSION['userEmail'];
		$roleID = $_SESSION['roleID'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT userPassword, roleID FROM users WHERE userID = ? LIMIT 1")) 
		{
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $userID);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) 
	    	{
                // If the user exists get variables from result.
                $stmt->bind_result($password, $roleID);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
		
                if ($login_check == $login_string) 
				{
                    // Logged In!!!! 
                    return true;
                } 
				else
				{
                	// Not logged in 
            		return false;
            	}
            } 
	    	else 
	    	{
				// Not logged in 
                return false;
       		}
        } 
		else 
		{
            // Not logged in 
            return false;
        }
    } 
    else 
    {
        // Not logged in 
        return false;
    }
}

function roleID_check($mysqli) 
{
	if (isset($_SESSION['roleID'], $_SESSION['userID'], $_SESSION['userEmail']))
	{
		$userID = $_SESSION['userID'];
		$roleID = $_SESSION['roleID'];
		if ($stmt = $mysqli->prepare("SELECT roleID FROM users where userID = ? LIMIT 1"))
		{
			$stmt->bind_param('i', $userID);
			$stmt->execute();
			$stmt->store_result();
				
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($dbRoleID);
				$stmt->fetch();


				if ($roleID == $dbRoleID)
				{	
					return $roleID;
				}
				else
				{
					return -1;
				}
			}	
			else
			{
				return -1;
			}
		}
		else 
		{
			return -1;
		}
	}
	else
	{
		return -1;
	}
}

function changePassword($email, $oldPassword, $newPassword, $mysqli) 
{
    // Using prepared statements means that SQL injection is not possible. 
    $stmt = $mysqli->prepare("UPDATE users SET userPassword = ? WHERE userEmail = ? AND userPassword = ?");
    
    $stmt->bind_param('sss', $newPassword, $email, $oldPassword);  // Bind "$email" to parameter.
    $stmt->execute();    // Execute the prepared query.
}

function esc_url($url) 
{
    if ('' == $url) 
    {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    
    while ($count) 
    {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') 
    {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } 
    else 
    {
        return $url;
    }
}
