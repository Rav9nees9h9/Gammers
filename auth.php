<?php
// access the current session
session_start();
//if the session id is empty, the user is not logged in, send to restricted page
if (empty($_SESSION['id'])) {
    header('Location:restricted.php');
    exit();
}

?>
