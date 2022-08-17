<!--require global header -->
<?php require_once('header.php'); ?>
<div class="container">
  <header>
    <h1> Gamers </h1>
    <h2> Fellow Gamers </h2>
  </header>
  <main class="view">
    <div class="row">
      <div class="col-md-8">
        <p> lokking for interesting stuffs- It's Game time </p>
        <p class="special-text">Games Like: </p>
        <ul>
          <li> Ping-pong </li>
          <li> Football </li>
          <li> Cricket </li>
          <li> SVG Illustrations from <a href="https://drawkit.com/"> https://drawkit.com/ </a></li>
        </ul>
      </div>

    </div>
    <div>
      <h3>Our Users</h3>
      <!-- dynamically populate the user list -->
        <?php
        //connect to db
        require_once('connect.php');
        //set up query
        $sql = "SELECT * FROM profile";
        //prepare
        $statement = $db->prepare($sql);
        //execute
        $statement->execute();
        // use fetchAll to get all remaining data rows in the set, store in variable called users
        $users = $statement->fetchAll();
        //create a div element
        echo "<div class='user_container'>";
        //loop through info stored in users using a foreach loop
        foreach ($users as $user) {
          echo "<div class='user'>";
          echo "<img src='images/" . $user['profile_image'] . "' alt='" . $user['username'] . "'>";
          echo "<p>" . $user['username'] . "</p>";
          echo "</div>";
        }
        //create the closing div
        echo "</div>";
        //close db connection
        $statement->closeCursor();
        ?>
      </div>
      <!--end row-->
  </main>
  <!--require global footer here -->
  <?php require_once('footer.php'); ?>
