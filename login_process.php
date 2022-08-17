<?php
/* We need to check the records in our table to see whether the user exists. If the user exists, we then need to verify that the password entered matches the hashed password stored. We can do this using password_verify() */


//validate & store form info

$input_user_login = filter_input(INPUT_POST, 'user_login');
$input_password = filter_input(INPUT_POST, 'password');


/* validation
1.) all form fields are completed
*/

if (empty($input_user_login)) {
    echo "<p> Please enter a username or email </p>";
} elseif (empty($input_password)) {
    echo "<p> Please enter a password </p>";
} else {
    try {
        ////connect to the db
        require_once 'connect.php';
        //set up query to grab user info from the table
        $sql = "SELECT user_id, username, password FROM profile WHERE username = :username OR email = :username";
        //prepare
        $stmt = $db->prepare($sql);
        //bind
        $stmt->bindParam(':username', $input_user_login);
        //execute
        $stmt->execute();
        //check if the user exists - i.e. there is a record in the table
        if ($stmt->rowCount() == 1) {
            //if so, fetch the information for the user
            if ($row = $stmt->fetch()) {
                $id = $row['user_id'];
                $username = $row['username'];
                $hashedpassword = $row['password'];
                //check if the passwords match using password_verify
                if (password_verify($input_password, $hashedpassword)) {
                    //start a session and store the user's information
                    session_start();
                    // Store data in session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username;
                    // Redirect user to members page - only logged in users should be able to view
                    header("location:Game.php");
                } else {
                    // Display an error message if password is not valid
                    $password_err = "The password you entered was not valid.";
                    echo $password_err;
                }
            }
        }
    } catch (Exception $e) {
        $errormessage = $e->getMessage();
        echo "<p> Opps! Something went wrong? </p>";
        echo $errormessage;
    } finally {
        $stmt->closeCursor();
    }
}
