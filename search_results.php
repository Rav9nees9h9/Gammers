<?php require_once('auth.php') ?>
<?php require_once 'header.php'; ?>
<div class="container">
    <?php
    //let's access the users search term and display on the page
    $search = filter_input(INPUT_GET, 'keywords');
    echo "<p class='alert alert-info'> You searched for the following: " . $search . "</p>";

    //let's use explode to break a search phrase into keywords

    $search_words = explode(' ', $search);

    //we use the explode function to break apart our search phrase. The first parameter is the boundary (i.e. where we should split it up - in this case it would be spaces between the words )

    //let's build the start of our query
    $sql = "SELECT * FROM Gamers WHERE ";

    //we don't know how many search terms we will end up with and we want to append them to our query using OR

    //let's use a loop to loop through the search terms stored in $search_words
    $where = "";
    foreach ($search_words as $word) {
        $where = $where . "favgame LIKE '%$word%' OR "; //we continue to build the query
    }

    $where = substr($where, 0, strlen($where) - 4);

    $final_query = $sql . $where;
    //connect to db first!
    require_once('connect.php');
    //prepare
    $statement = $db->prepare($final_query);
    //execute
    $statement->execute();
    //use fetchAll to access the rows in the data, store in $results
    $results = $statement->fetchAll();
    //loop through and display results
    //build a table element outside of the loop
    echo "<table class='table table-striped'>";
    foreach ($results as $result) {
        echo "<tr>";
        echo "<td>" . $result['first_name']  . "</td>";
        echo "<td>" . $result['last_name'] . "</td>";
        echo "<td>" . $result['email'] . "</td>";
        echo "<td>" . $result['game'] . "</td>";
    }

    //close the table element
    echo "</table>";

    //close cursor
    $statement->closeCursor();

    ?>
</div><!-- close .container-->
<?php require_once 'footer.php'; ?>
