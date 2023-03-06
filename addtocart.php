<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
    if(isset($_GET['id']) && isset($_GET['q'])){
        $quantity= $_GET['q'];
        $comb_id = $_GET['id'];
        $user_id=$_SESSION['id'];
        require_once('connection.php');
        $query = "SELECT * FROM carts WHERE user_id = '$user_id' AND combination_id = '$comb_id'";
        $result = mysqli_query($link,$query);
            $row = mysqli_fetch_assoc($result);
            if($row!=0){
            $old_quantity = $row['quantity'];
            $quantity += $old_quantity;
            $query = "UPDATE carts SET quantity='$quantity'";
            $result = mysqli_query($link,$query);
            if($result){
                echo 'Added to Cart';
            }else{
                echo 'Failed';
            }
        }else{
            $query = "INSERT INTO carts(user_id, combination_id, quantity) VALUES ('".$user_id."','".$comb_id."','".$quantity."')";
            $result = mysqli_query($link,$query);
            if($result){
                echo 'Added to Cart';
            }else{
                echo 'Failed';
            }
        }
    }else{
        echo'Error';
    }
}else{
    echo'Please Login First';
}
?>