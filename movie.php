<!--
NICHOLAS LINGAD
--> 

<?php
    $db = new mysqli('localhost', 'cs143', '', 'class_db');
    if ($db->connect_errno > 0) { 
        die('Unable to connect to database [' . $db->connect_error . ']'); 
    }

    $id = $_GET['id'];
    // Display Movie Title 
    $statement = $db->prepare("SELECT title, year, rating, company FROM Movie WHERE id=?");
    $statement->bind_param('i', $id);
    $statement->execute();

    $statement->bind_result($returned_title, $returned_year, $returned_rating, $returned_company);
    $statement->fetch(); // Should only be one result because id is unique identifier 
    $statement->close();
    $mid = $id;
    $query = "SELECT genre FROM MovieGenre WHERE mid={$mid}";
    $rs = $db->query($query);
    $row = $rs->fetch_assoc(); 
    $returned_genre = $row['genre'];
    $rs->free();
?> 

<!DOCTYPE html> 
<html>
<body>
<div>
    <div><h1>Movie Information</h1> 
    <?php echo 'Movie Title: ' . $returned_title . '<br>' . 
    ' Year: ' . $returned_year . '<br>' .
    ' Genre: ' . $returned_genre . '<br>' .  
    ' Rating: ' . $returned_rating . '<br>' . 
    ' Company: ' . $returned_company . '<br>';
    ?></div> 

    <div><h1>Actors</h1> 
    <table name = "movieActors"> 
    
        <th>Name</th> 
        <?php 
           $query = "SELECT DISTINCT Actor.id actorid, last, first
           FROM Actor, MovieActor 
           WHERE Actor.id IN (SELECT aid FROM MovieActor WHERE MovieActor.mid={$mid}) 
           AND Actor.id=MovieActor.aid" ; 
           $rs = $db->query($query); 
           while ($row = $rs->fetch_assoc()) {
               $actorid = $row['actorid'];
               echo '<tr>'; 
               echo '<td>'. "<a href=\"./actor.php?id={$actorid}\"]>" . $row['first'] . ' ' . $row['last'] . '</a>' . '</td>';
               echo '</tr>'; 
           }
           $rs->free();
        ?> 
    </table> 
    </div> 
    <div>
        <h1> Reviews </h1> 
        <h1>Average review:         
        <?php // TODO: TEST WHEN FINISH REVIEW PAGE
        
        $query = "SELECT AVG(rating) avg FROM Review WHERE mid={$mid}"; 
        $rs = $db->query($query); 
        $row = $rs->fetch_assoc(); 
        $returned_avg = $row['avg'];
        if (!isset($returned_avg)) {
            $returned_avg = 0;
        }
        echo $returned_avg . '/5'; 
        $rs->free();
        ?> </h1>

    </div> 

    <div> 
        <h2><?php echo "<a href=\"./review.php?id={$mid}\"> Add comment </a>"; ?> </h2> 
        <h2>Comments</h2> 
        <?php // TODO: TEST WHEN FINISH REVIEW PAGE 
        $query = "SELECT name, time, rating, comment FROM Review WHERE mid={$mid} ORDER BY time DESC"; 
        $rs = $db->query($query); 
        while ($row = $rs->fetch_assoc()) {
            $name = $row['name'];
            $time = $row['time'];
            $rating = $row['rating']; 
            $comment = $row['comment']; 
            echo '<p><b>Name: </b>' . $name . '&emsp;' . 
            '<b>Time: </b>' . $time . '&emsp;' . 
            '<b>Rating: </b>' . $rating . '/5' . '<br>' . 
            $comment . '</p>';  
        }
        $rs->free();
        ?>
    </div> 
</div>


</body>
</html>

<?php 
    $db->close();
?> 