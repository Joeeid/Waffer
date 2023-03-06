<?php
session_start();
require_once('connection.php');
$totalquantity=0;
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
        $id = $_SESSION['id'];
        $query = "SELECT * FROM carts WHERE user_id = '$id'";
        $result = mysqli_query($link,$query);
        while($row = mysqli_fetch_assoc($result)){
            $totalquantity += $row['quantity'];
        }
    }
echo $totalquantity;
?>