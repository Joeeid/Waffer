<?php
if(isset($_GET['q'])){
    $q = $_GET['q'];
    require_once('connection.php');
    $query = "SELECT * FROM ((products INNER JOIN markets_products ON products.product_id = markets_products.product_id) 
    INNER JOIN markets ON markets_products.market_id = markets.market_id) WHERE product_name LIKE '%$q%' OR market_name LIKE '%$q%';";
    $result = mysqli_query($link,$query);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            echo '<a href = productdetails.php?pid='.$row['product_id'].'>';
            echo $row['product_name'].' - '.$row['market_name'].'<br/>'.$row['price'].' L.L.</a><br/><br/>';
        }
    }
}
mysqli_close($link);
?>