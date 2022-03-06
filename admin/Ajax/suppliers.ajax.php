<?php
    include "connection.php";
    $do = htmlspecialchars($_GET['do']) ?? null;

    $idsupp = $_POST['idsupp'] ?? null;
    if($do == "showPaiementHistory") {
        $stmt = $conn->prepare("SELECT * FROM zzpaysupplier WHERE ppidsupp = ? ORDER BY ppid DESC");
        $stmt->execute([$idsupp]);
        $paySupplier = $stmt->fetchAll();
        header("content-type: application/json");
        echo json_encode($paySupplier);
    
    }

?>
