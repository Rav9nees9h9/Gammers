  <!-- this form will allow users to register/join tuneshare. Registration is needed in order to access playlist and to be able to add a song to the playlist -->
  <!-- require global header -->
  <?php require_once('header.php'); ?>
  <div class="container">
    <header>
      <h1> Gamers </h1>
      <h2> Fellow_Gamers </h2>
    </header>
    <main>
      <?php

      //if the form has been submited, process the form information
      if (isset($_POST['submit'])) {
        //check whether the recaptcha was checked by the user
        if (!empty($_POST['g-recaptcha-response'])) {
          //create variables to store form data, using filter input to validate & sanitize
          /*https://www.php.net/manual/en/filter.filters.sanitize.php*/
          $input_firstname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS);
          $input_lastname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS);
          $input_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL, FILTER_SANITIZE_EMAIL);
          $input_game = filter_input(INPUT_POST, 'fgame', FILTER_SANITIZE_SPECIAL_CHARS);
          $input_username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
          $input_password = filter_input(INPUT_POST, 'password');
          $input_password_confirm = filter_input(INPUT_POST, 'password_confirm');
          /* image */
          $photo = $_FILES['photo']['name'];
          $photo_type = $_FILES['photo']['type'];
          $photo_size = $_FILES['photo']['size'];
          $photo_tmp = $_FILES['photo']['tmp_name'];
          $photo_error = $_FILES['photo']['error'];
          $id = null;
          $id = filter_input(INPUT_POST, 'user_id');

          //recaptcha
          $secret = '6LfePG4gAAAAAASIYzASMEqTP1LD44Q5LVsv3NU9';
          $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);

          $responseData = json_decode($verifyResponse, true);

          //form validation
          require('validate.php');

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

              //move the uploaded image from temporary directory to images folder
              $target = 'images/'. $photo;
              move_uploaded_file($photo_tmp, $target);

              //hash the password
              $hashed_password = password_hash($input_password, PASSWORD_DEFAULT);

              // set up SQL command to insert data into table

              $sql = "INSERT INTO profile (first_name, last_name,email,game, username, password, profile_image) VALUES (:firstname, :lastname,:email, :game,:username, :password, :profile_image)";


              //call the prepare method of the PDO object, return PDOStatement Object
              $statement = $db->prepare($sql);

              //bind parameters
              $statement->bindParam(':firstname', $input_firstname);
              $statement->bindParam(':lastname', $input_lastname);
              $statement->bindParam(':email', $input_email);
              $statement->bindParam(':number', $input_game);
              $statement->bindParam(':username', $input_username);
              $statement->bindParam(':profile_image', $photo);
              //ensure you are storing the hashed version!
              $statement->bindParam(':password', $hashed_password);

              //execute the query
              $statement->execute();

              //redirect the user to the login page to allow them to login
              header("Location: login.php");
            } catch (Exception $e) {
              $error_message = $e->getMessage();

              error_log($error_message, 3, "my-error-file.log");
              //redirect user to custom error page
              header("Location: error.php");
            } finally {
              //close the db connection
              $statement->closeCursor();
            }
          }
        } else {
          echo "<p class='alert alert-danger'> Please let us know you are not a robot! </p>";
        }
      }
      ?>
      <div class="row">
        <!--the HTML registration form-->
        <div class="col-md-6">
          <!--add enctype to form -->
          <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="form" enctype="multipart/form-data">
            <!-- add hidden input with user id if editing -->
            <input type="hidden" name="user_id">
            <div class="form-group">
              <label for="fname"> Your First Name </label>
              <input type="text" name="fname" class="form-control" id="fname" required>
            </div>
            <div class="form-group">
              <label for="lname"> Your Last Name </label>
              <input type="text" name="lname" class="form-control" id="lname" required>
            </div>
            <div class="form-group">
              <label for="email"> Your Email </label>
              <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="form-group">
              <label for="fgame"> Your Game: </label>
              <input type="text" name="fgame" class="form-control" id="fgame" required>
            </div>
            <div class="form-group">
              <label for="email"> Password </label>
              <input type="password" name="password" class="form-control" id="email" required>
            </div>
            <div class="form-group">
              <label for="email"> Password Confirm </label>
              <input type="password" name="password_confirm" class="form-control" id="email"  required>
            </div>
            <!--add input type = file -->
            <div class="form-group">
              <label for="profile"> Profile Pic </label>
              <input type="file" name="photo" id="profilepic">
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
