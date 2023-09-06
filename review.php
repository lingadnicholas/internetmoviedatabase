<!--
NICHOLAS LINGAD
--> 

<!DOCTYPE HTML> 
<html> 
<body>
    <?php 
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $db = new mysqli('localhost', 'cs143', '', 'class_db');
                if ($db->connect_errno > 0) { 
                    die('Unable to connect to database [' . $db->connect_error . ']'); 
                }
            $query = "SELECT title FROM Movie WHERE id={$id}";

            $rs = $db->query($query);
            while ($row = $rs->fetch_assoc()) {
                $title = $row['title'];
            }
            $rs->free();
            $db->close();
        } 
        if (isset($_POST['rating'])) {
            $name = $_POST['name'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment']; 
            
        
            $db = new mysqli('localhost', 'cs143', '', 'class_db');
            if ($db->connect_errno > 0) { 
                die('Unable to connect to database [' . $db->connect_error . ']'); 
            }
            $query = "INSERT INTO Review VALUES ('{$_POST['name']}', NOW(), {$_POST['mid']}, {$_POST['rating']}, '{$_POST['comment']}')"; //TODO: check query is correct
            $rs = $db->query($query); 
            $db->close(); 
            echo 'Your review has successfully been added. <br>'; 
            echo "<a href=\"./movie.php?id={$id}\">Link to movie</a>";
        }
        else {
            echo "<h3>Movie: {$title}</h3>
            <FORM METHOD=\"POST\" ACTION=\"\"> 
            <p> Name: <INPUT type=\"text\" NAME=\"name\" VALUE=\"Type name here\" SIZE=\"10\" MAXLENGTH=\"20\"> </p>  
            <p> Rating: <SELECT NAME=\"rating\"> 
                <OPTION>1</OPTION>
                <OPTION>2</OPTION>
                <OPTION>3</OPTION>
                <OPTION>4</OPTION>
                <OPTION>5</OPTION>
            </SELECT> </p>  
            <p> Comment: </p> <TEXTAREA NAME=\"comment\" ROWS=\"5\" COLS=\"30\"> Insert your comment here! 
            </TEXTAREA> <br> 
            <INPUT TYPE=\"hidden\"  name=\"mid\" value=\"{$id}\">
            <INPUT TYPE=\"submit\" VALUE=\"Add comment\" NAME=\"submitbutton\"> 
            </FORM>";

        }


        ?> 

</body>
</html> 