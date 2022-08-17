<?php
/*first create a new PDO object with the data source name, user, passsword. The PDO object is an instance of the PDO class.
PDO uses a data source name (DSN) that contains the following information:*/
//The database server host
$host = '172.31.22.43';
//The database name
$db = 'Ravneesh200506395';
//The user
$user = 'Ravneesh200506395';
//The password
$password = '11fMy3diph';
//error #1 - missing host=
$dsn = "mysql:host=$host;dbname=$db";


//error handling with try catch blocks
try {
    $conn = new PDO ($dsn, $user, $password,);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //#2 - add some logic
    if($conn) {
        echo "<p> Successfully connected! </p>";
    }
}
// error #3 - PDOException
catch(PDOException $e) {
    //#4 final " in the wrong spot
    echo "<p> Unable to establish a connection :" . $e->getMessage();
}
