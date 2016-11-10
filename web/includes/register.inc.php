<?php
include_once 'dbConnect.php';
include_once 'dbConfig.php';
 
$error_msg = "";
 
if (isset($_POST['username'], $_POST['email'], $_POST['p'])) 
{
    // Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
 
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

    if (strlen($password) != 128) 
    {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
 
    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
    echo $email . "\n";

    $prep_stmt = "SELECT id FROM users WHERE email = ? LIMIT 1";
    //$prep_stmt = "SELECT * FROM users";
    $stmt = $mysqli->prepare($prep_stmt);
   // check existing email  
    if ($stmt) 
    {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows == 1) 
        {
            // A user with this email address already exists
            $error_msg .= '<p class="error">A user with this email address already exists.</p>';
                        $stmt->close();
        }
        $stmt->close();
    } 
    else 
    {
        $error_msg .= '<p class="error">Database error Line 39</p>';
        $stmt->close();
    }
    // check existing username
    $prep_stmt = "SELECT id FROM users WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
 
    if ($stmt) 
    {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
	
	if ($stmt->num_rows == 1) 
	{
                // A user with this username already exists
		$error_msg .= '<p class="error">A user with this username already exists</p>';
                $stmt->close();
        }
       $stmt->close();
    } 
    else 
    {
    	$error_msg .= '<p class="error">Database error line 55</p>';
        $stmt->close();
    }
 
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.
 
    if (empty($error_msg)) 
    {
        // Create a random salt
        //$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE)); // Did not work
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

	$tempPassword = $password;
 
        // Create salted password 
        $password = hash('sha512', $password . $random_salt);
 
	if (DEBUG):
                echo "Username: " . $username . "\n";
		echo "Email: " . $email . "\n";
                echo "Salt: " . $random_salt . "\n";
                echo "Entered Password: " . $tempPassword . "\n";
                echo "Hashed Entered Password: " . $password . "\n";
        endif;
        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->prepare("INSERT INTO users (username, email, password, userSalt) VALUES (?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssss', $username, $email, $password, $random_salt);
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
 //               header('Location: ../error?err=Registration failure: INSERT');
		if (DEBUG):
			printf("%s\n", $insert_stmt->error);
			echo "Could not insert into database";
		endif;
            }
        }
        header('Location: ../pages/createAccount');
    }
}
