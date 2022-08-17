<?php
// The script that will delete the information from the table in the database

//store the user id in a variable - post or get?
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, FILTER_SANITIZE_NUMBER_INT);

//validate whether user id is set and is a number
try {
    if (!empty($user_id) && $user_id !== false) {
        //connect to the database
        require_once('connect.php');
        //create our SQL statement
        $sql = "DELETE FROM Gamers WHERE user_id = :user_id";
        //prepare the statement
        $statement = $db->prepare($sql);
        //bindParam
        $statement->bindParam(':user_id', $user_id);
        //execute
        $statement->execute();
        header("Location: Game.php");
         //close db connection
        $statement->closeCursor();
    }
} catch (PDOException $e) {
    header('Location: error.php');
    $error_message = $e->getMessage();
    $msg = "There was an error" . $error_message . ".";
    //send error email to dev/admin
    mail("Ravneesh200506395@gmail.com", "Error", $msg);
    //log the error in custom error log
    error_log($msg, 3, "my-error-file.log");
}
