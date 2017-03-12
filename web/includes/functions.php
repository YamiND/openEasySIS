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
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT userID, userPassword, userSalt 
        FROM users WHERE userEmail = ? LIMIT 1")) 
    {
        $stmt->bind_param('s', $userEmail);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($userID, $dbPassword, $userSalt);
        $stmt->fetch();
	
		$tempPassword = $password;  // This value is the hashed passwodr passed in
        // hash the password with the unique salt.
        $password = hash('sha512', $password . $userSalt);
	
        if ($stmt->num_rows == 1) 
		{
                if ($dbPassword == $password) 
                {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $userID = preg_replace("/[^0-9]+/", "", $userID);
                    $_SESSION['userID'] = $userID;
					
                    // XSS protection as we might print this value
		    		$_SESSION['userEmail'] = $userEmail;
                    $_SESSION['login_string'] = hash('sha512', 
                              $dbPassword . $user_browser);
		
			// all session variables are set at this point	
                    // Login successful.
                    return true;
                } 
                else 
                {
                    return false;
                } 
        } 
        else 
        {
            // No user exists.
            return false;
        }
    }
}

function login_check($mysqli) 
{
    // Check if all session variables are set 
    if (isset($_SESSION['userID'], $_SESSION['userEmail'], $_SESSION['login_string'])) 
    {
        $userID = $_SESSION['userID'];
        $login_string = $_SESSION['login_string'];
		$userEmail = $_SESSION['userEmail'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT userPassword FROM users WHERE userID = ? LIMIT 1")) 
		{
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $userID);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) 
	    	{
                // If the user exists get variables from result.
                $stmt->bind_result($password);
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

function isAdmin($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];

		if ($stmt = $mysqli->prepare("SELECT isAdmin FROM users WHERE userID = ? LIMIT 1"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows == 1)
				{
					$stmt->bind_result($isAdmin);

					$stmt->fetch();

					return $isAdmin;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function isSchoolAdmin($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];

		if ($stmt = $mysqli->prepare("SELECT isSchoolAdmin FROM users WHERE userID = ? LIMIT 1"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows == 1)
				{
					$stmt->bind_result($isSchoolAdmin);

					$stmt->fetch();

					return $isSchoolAdmin;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function isTeacher($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];

		if ($stmt = $mysqli->prepare("SELECT isTeacher FROM users WHERE userID = ? LIMIT 1"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows == 1)
				{
					$stmt->bind_result($isTeacher);

					$stmt->fetch();

					return $isTeacher;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function isParent($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];

		if ($stmt = $mysqli->prepare("SELECT isParent FROM users WHERE userID = ? LIMIT 1"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows == 1)
				{
					$stmt->bind_result($isParent);

					$stmt->fetch();

					return $isParent;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function isStudent($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];

		if ($stmt = $mysqli->prepare("SELECT isStudent FROM users WHERE userID = ? LIMIT 1"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows == 1)
				{
					$stmt->bind_result($isStudent);

					$stmt->fetch();

					return $isStudent;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function canModClassList($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];
		
		if ($stmt = $mysqli->prepare("SELECT modClassList FROM users WHERE userID = ?"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->bind_result($modClassList);
				$stmt->store_result();
		
				$stmt->fetch();

				return $modClassList;	
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function canViewAllGrades($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];
		
		if ($stmt = $mysqli->prepare("SELECT viewAllGrades FROM users WHERE userID = ?"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->bind_result($viewAllGrades);
				$stmt->store_result();
		
				$stmt->fetch();

				return $viewAllGrades;	
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

?>
