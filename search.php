<!DOCTYPE HTML> 
<html> 
<body>
    <h1>Searching Page:</h1>
    <form method="GET" action="./search.php">
    <h3>Actor Name:</h3>
    <input type="text" id="actor" name="actor" placeholder="Julia Roberts"><br><br>
    <input type="submit" id="submitActor" value="Search Actors!">
    </form>

    <form method="GET" action="./search.php">
    <h3>Movie Name:</h3>
    <input type="text" id="movie" name="movie" placeholder="The Matrix"><br><br>
    <input type="submit" id="submitMovie" value="Search Movies!">
    </form>
    <?php 
        if (isset($_GET['actor'])) {
            $keywords = explode(" ", $_GET['actor']);
            $db = new mysqli('localhost', 'cs143', '', 'class_db');
            if ($db->connect_errno > 0) { 
                die('Unable to connect to database [' . $db->connect_error . ']'); 
            }

            echo "<div>
            <h3>Actors and Date of Birth:</h3>
            <table>
            <tr>
                <th>Name</th>
                <th>Date of Birth</th> 
            </tr>";
            // FIX THE QUERY
            // repeatedly perform the query until out of elements in keywords? each time look for NOT IN current results
            $curRes;
            for ($i = 0; $i < count($keywords); $i++) {
                $strKeyword = '"%' . $keywords[$i] . '%"';
                $query = "SELECT first, last, dob, id
                FROM Actor
                WHERE (LOWER(first) LIKE LOWER({$strKeyword})) OR (LOWER(last) LIKE LOWER({$strKeyword}))";

                $rs = $db->query($query);
                if ($i == 0) {
                    $curRes = mysqli_fetch_all($rs);
                } else {
                    $res = mysqli_fetch_all($rs);
                    $updateElements = [];
                    for ($row = 0; $row < count($res); $row++) {
                        if (in_array($res[$row], $curRes)) {
                            array_push($updateElements, $res[$row]);
                        }
                    }
                    $curRes = $updateElements;
                }
            }
            
            for ($row = 0; $row < count($curRes); $row++) {
                //echo $curRes[$row][0];
                $first = $curRes[$row][0];
                $last = $curRes[$row][1];
                $dob = $curRes[$row][2];
                $aid = $curRes[$row][3];
                echo '<tr>'; 
                echo '<td>'. "<a href=\"./actor.php?id={$aid}\"]>" . $first . ' ' . $last . '</a>' . '</td>';
                echo '<td>'. $dob . '</td>';
                echo '</tr>'; 
            }
            $rs->free();
            $db->close();

            echo "</table>
            </div>";
        } else if (isset($_GET['movie'])) {
            $keywords = explode(" ", $_GET['movie']);
            $db = new mysqli('localhost', 'cs143', '', 'class_db');
            if ($db->connect_errno > 0) { 
                die('Unable to connect to database [' . $db->connect_error . ']'); 
            }

            echo "<div>
            <h3>Movies and Years:</h3>
            <table>
            <tr>
                <th>Movie</th>
                <th>Year</th> 
            </tr>";
            // TODO: FIX THE QUERY

            $curRes;
            for ($i = 0; $i < count($keywords); $i++) {
                $strKeyword = '"%' . $keywords[$i] . '%"';
                $query = "SELECT title, year, id
                FROM Movie
                WHERE (LOWER(title) LIKE LOWER({$strKeyword}))";

                $rs = $db->query($query);
                if ($i == 0) {
                    $curRes = mysqli_fetch_all($rs);
                } else {
                    $res = mysqli_fetch_all($rs);
                    $updateElements = [];
                    for ($row = 0; $row < count($res); $row++) {
                        if (in_array($res[$row], $curRes)) {
                            array_push($updateElements, $res[$row]);
                        }
                    }
                    $curRes = $updateElements;
                }
            }
            
            for ($row = 0; $row < count($curRes); $row++) {
                //echo $curRes[$row][0];
                $title = $curRes[$row][0];
                $year = $curRes[$row][1];
                $mid = $curRes[$row][2];
                echo '<tr>'; 
                echo '<td>'. "<a href=\"./movie.php?id={$mid}\"]>" . $title . '</a>' . '</td>';
                echo '<td>'. $year . '</td>';
                echo '</tr>'; 
            }
            $rs->free();
            $db->close();
            
            echo "</table>
            </div>";
        }
    ?> 

</body>
</html>