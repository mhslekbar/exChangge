<?php
    session_start();
    ob_start();
    if(isset($_SESSION['username'])):
        include "init.php";
        
        echo "<a href='logout.php'>sniper</a>";
        
        include $tpl . "footer.php";
    else:
        header("Location: ../index.php");
    endif;
    ob_end_flush();
?>