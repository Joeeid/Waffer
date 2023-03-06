<?php
    require_once('connection.php');
    extract($_GET);
    $result=mysqli_query($link,"SELECT * FROM delivery WHERE (dest1='$d1' AND dest2='$d2') OR (dest1='$d2' AND dest2='$d1')");
    while($row = mysqli_fetch_assoc($result)){
        echo $row['charge'].' L.L.';
    };
?>