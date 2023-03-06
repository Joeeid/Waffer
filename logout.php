<?php
    session_start();
    session_unset();
    session_destroy();
    setcookie('last_page',"",time()-10);
    header("Location: index.php");
?>