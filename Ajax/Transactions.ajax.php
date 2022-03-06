<?php
    include "connection.php";
    $do = isset($_GET['do']) ? htmlspecialchars($_GET['do']) : null; 
    $toCurr = $_POST['toCurr'] ?? null;
    if($do == "getCurrencyPrice") {
        echo getCurrencyPrice($toCurr)['cost_price'] . " " . getCurrencyPrice($toCurr)['retail_price'] . " ". getCurrencyPrice($toCurr)['currencyRR'];
    }

    function getCurrencyPrice($toCurr) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM ratebranchs WHERE idRR = ?");
        $stmt->execute([$toCurr]);
        return $stmt->fetch();
    }
?>