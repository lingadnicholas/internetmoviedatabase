<?php
    $db = new mysqli('localhost', 'cs143', '', 'class_db');
    if ($db->connect_errno > 0) { 
        die('Unable to connect to database [' . $db->connect_error . ']'); 
    }

    $id = $_GET['id'];
    $statement = $db->prepare("SELECT * FROM Actor WHERE id=?");
    $statement->bind_param('i', $id);
    $statement->execute();
    
    $statement->bind_result($returned_id, $last_name, $first_name, $sex, $dob, $dod);
    $statement->fetch(); // Only one result because we're finding by a unique id

    if ($dod === NULL) {
        $dod = 'Still Alive';
    }
    $aid = $returned_id;

    $statement->close();
?>

<!DOCTYPE HTML>

<html>
<body>
<div>
    <div><h1>Actor Information:</h1>
    <table>
    <tr>
        <th>Name</th>
        <th>Sex</th> 
        <th>Date of Birth</th>
        <th>Date of Death</th>
    </tr>
    <tr>
        <?php 
           echo '<td>' . $first_name . ' ' . $last_name . '</td>';
           echo '<td>' . $sex . '</td>';
           echo '<td>' . $dob . '</td>';
           echo '<td>' . $dod . '</td>';
        ?> 
    </tr>
    </table>
    </div>

    <div>
        <h1>Actor's Movies and Role:</h1>
        <table>
        <tr>
            <th>Role</th>
            <th>Movie Title</th> 
        </tr>
        <?php 
        // TODO: this query doesn't work.
            $query = "SELECT Movie.title title, MovieActor.role role, Movie.id mid
            FROM MovieActor, Movie
            WHERE MovieActor.aid={$aid} AND MovieActor.mid = Movie.id";
            $rs = $db->query($query); 
            while ($row = $rs->fetch_assoc()) {
                $title = $row['title'];
                $role = $row['role'];
                $mid = $row['mid'];
                echo '<tr>'; 
                echo '<td>'. $role . '</td>';
                echo '<td>'. "<a href=\"./movie.php?id={$mid}\"]>" . $title . '</a>' . '</td>';
                echo '</tr>'; 
            }
            $rs->free();
        ?> 
        </table>
    </div>
</div>
</body>
</html>

<?php
$db->close();
?>