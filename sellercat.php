<?php
      require_once('connection.php');
      session_start();
      $mid = $_SESSION['mid'];
      $cat = $_GET['c'];
      $result=mysqli_query($link,"SELECT * FROM products p WHERE NOT EXISTS ( SELECT NULL FROM markets_products mp WHERE mp.market_id = $mid AND mp.product_id = p.product_id) AND p.category_id = $cat");
      echo '<option value="0">Select product</option>';
      while($row = mysqli_fetch_assoc($result)){
         echo '<option value = "'.$row['product_id'].'">'.$row['product_name'].' - '.$row['description'].'</option>';
      }
      ?>