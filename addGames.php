<!--only authenticated users should see this content -->
<?php require "auth.php" ?>
<!-- require global header -->
<?php require_once('header.php'); ?>
<div class="container">
    <header>
        <h1> Gamers </h1>
        <h2> Fellow-Gamers </h2>
    </header>
    <main>
        <?php
        //intialize variables
        $firstname = null;
        $lastname = null;
        $email = null;
        $favGame = null;

        //get song id from URL string if present/editing
        $userid = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        //if the song id is not empty, we are editing
        if (!empty($userid)) {
            try {
                //connect to the database
                require_once('connect.php');
                //set up our query
                $sql = "SELECT * FROM Gamers WHERE user_id = :user_id;";
                //prepare our statement
                $statement = $db->prepare($sql);
                //bind
                $statement->bindParam(':user_id', $userid);
                //execute
                $statement->execute();
                //use fetchAll to store
                $songs = $statement->fetchAll();
                //to loop through, use a foreach loop to access the table data and store in varaibles
                foreach ($records as $record) :
                  $firstname = $record['first_name'];
                  $lastname = $record['last_name'];
                  $email = $record['email'];
                  $favGame = $record['game'];
                endforeach;
                //close the db connection
                $statement->closeCursor();
            } catch (PDOException $e) {
                header('location:error.php');
            }
        }
        //if the form has been submited, process the form information
        else if (isset($_POST['submit'])) {
            //check whether the recaptcha was checked by the user
            //create variables to store form data, using filter input to validate & sanitize
            /*https://www.php.net/manual/en/filter.filters.sanitize.php*/
            $input_firstname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS);
            $input_lastname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS);
            $input_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL, FILTER_SANITIZE_EMAIL);
            $input_game = filter_input(INPUT_POST, 'fgame', FILTER_SANITIZE_SPECIAL_CHARS);

            $id = null;
            $id = filter_input(INPUT_POST, 'user_id');


            $secret = '6LfePG4gAAAAAASIYzASMEqTP1LD44Q5LVsv3NU9';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);

            $responseData = json_decode($verifyResponse, true);

            //create an empty errors array to store error messages
            $errors = [];

            //form validation
            if (empty($input_firstname) || empty($input_lastname) || empty($input_email) || empty($input_game)) {
                $error_msg = "Don't forget to share the information!";
                array_push($errors, $error_msg);
            }

            //if there are errors, display them
            if (!empty($errors)) {
                echo "<div class='error_msg alert alert-danger'>";
                foreach ($errors as $error) {
                    echo "<p>" . $error . "</p>";
                }
                echo "</div>";
                //no errors, go ahead and process the form
            } else {
                try {
                    //connect to database
                    require_once('connect.php');

                    // set up SQL command to insert data into table
                    //if we have an id, we are editing (UPDATE), if not, we will be adding information to the table (INSERT)

                    //if not empty, we are editing an exisiting record in the database table
                    if (!empty($id)) {
                        $sql = "UPDATE Gamers SET first_name = :firstname, last_name = :lastname,email = :email, game = :game,WHERE user_id = :id";
                    //if id is empty, we are creating a new record in the database table
                    } else {
                        // set up an SQL command to save the info
                        $sql = "INSERT INTO Gamers (first_name, last_name, email,game) VALUES (:firstname, :lastname, :email, :game);";
                    }

                    //call the prepare method of the PDO object, return PDOStatement Object
                    $statement = $db->prepare($sql);

                    //bind parameters
                    $statement->bindParam(':firstname', $input_firstname);
                    $statement->bindParam(':lastname', $input_lastname);
                    $statement->bindParam(':email', $input_email);
                    $statement->bindParam(':game', $input_game);
                    //bind user id if needed (editing)
                    if (!empty($id)) {
                        $statement->bindParam(':id', $id);
                    }

                    //execute the query
                    $statement->execute();

                    //redirect the user to the updated playlist page
                    header("Location: Game.php");
                } catch (Exception $e) {
                    $error_message = $e->getMessage();

                    error_log($error_message, 3, "my-error-file.log");
                    //redirect user to custom error page
                    header('Location: error.php');
                } finally {
                    //close the db connection
                    $statement->closeCursor();
                }
            }
        }
        ?>
        <!-- Here's the html form! -->
        <div class="row">
        <div class="col-md-6">
          <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="form">
            <!-- add hidden input with user id if editing -->
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <div class="form-group">
              <label for="fname"> Your First Name </label>
              <input type="text" name="fname" class="form-control" id="fname" value="<?php echo $firstname; ?>" required>
            </div>st
            <div class="form-group">
              <label for="lname"> Your Last Name </label>
              <input type="text" name="lname" class="form-control" id="lname" value="<?php echo $lastname; ?>" required>
            </div>
            <div class="form-group">
              <label for="email"> Your Email </label>
              <input type="email" name="email" class="form-control" id="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
              <label for="fgame"> Game:</label>
              <input type="text" name="fgame" class="form-control" id="fgame" value="<?php echo $number; ?>" required>
            </div>
            <!-- add the recpatcha widget -->
            <div class="g-recaptcha" data-sitekey="6LfePG4gAAAAADZ2lT_AlrPCCXB65KnudqxZMsdZ"></div>
            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
          </form>
        </div>
      </div>
        <!--end row-->
    </main>
    <!-- require global footer -->
    <?php require_once('footer.php'); ?>
