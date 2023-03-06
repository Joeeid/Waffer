<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
    $id=$_SESSION['id'];
    require_once('connection.php');
    $query = "SELECT * FROM ((carts INNER JOIN markets_products ON carts.combination_id = markets_products.combination_id)
    INNER JOIN products ON markets_products.product_id = products.product_id) WHERE user_id = '".$id."'";
    $result = mysqli_query($link,$query);
    if($result){
        $total = 0;
        $count = 0;
        while($row = mysqli_fetch_assoc($result)){
            echo '<div class="box" name="item">
            <i class="fas fa-trash" onclick="removeCart('.$row['combination_id'].','.$count.')"></i>
            <div class="content">
                <h3>'.$row['product_name'].'</h3>
                <span class="price">'.$row['price'].' L.L.</span>
                <span class="quantity">qty : '.$row['quantity'].'</span>
            </div>
        </div>';
        $total += $row['price']*$row['quantity'];
        $count++;
        }
        if($total==0){
                echo '<h3>hmmm... I think you should add some items</h3>';
        }
        echo '<div class="total"> total : '.$total.' L.L. </div>';
        if($total != 0){
        echo '<a href="checkout.php" class="btn">checkout</a>';
        }
    }
    mysqli_close($link);
}else{
    echo '<h3>Login to start shopping</h3>';
}
?>
