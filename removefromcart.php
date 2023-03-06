<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
    if(isset($_GET['id'])){
        $comb_id = $_GET['id'];
        $user_id=$_SESSION['id'];
        require_once('connection.php');
        $query = "DELETE FROM carts WHERE user_id = '$user_id' AND combination_id = '$comb_id'";
        $result = mysqli_query($link,$query);
            if($result){
                echo 'Removed from Cart';
            }else{
                echo 'Failed';
            }
    }else{
        echo'Error';
    }
}else{
    echo'Please Login First';
}
?>