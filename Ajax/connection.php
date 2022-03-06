<?php
    
    $host   = "localhost";
    $dbname = "exchangemgt";
    $user   = "root";
    $pass   = "";

    try {
        $conn = new PDO("mysql:host={$host};dbname={$dbname}",$user,$pass);
    } catch(PDOException $e) {
        echo "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
    }


?>