<?php
    include "connection.php";
    
    $do ="";
    if(isset($_GET['do'])){
        $do = htmlspecialchars($_GET['do']);
    }

    $idbranch = isset($_POST['idbranch']) ? htmlspecialchars($_POST['idbranch']) : null;
    // $idbranch = htmlspecialchars($_POST['idbranch']) && is_numeric($_POST['idbranch']);

    if($do == "getCurrencyType") :
        $currency = getidCurrency($idbranch);
        $devise = getDevise($currency['bbCurrencyType']);
        echo $devise['currencyRR'] . ":" . $devise['cost_price'];
    endif;

    function getidCurrency($idbranch) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM branchs WHERE bbid = ? ");
        $stmt->execute([$idbranch]);
        return $stmt->fetch();
    }

    function getDevise($idRR) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM rates WHERE idRR = ? ");
        $stmt->execute([$idRR]);
        return $stmt->fetch();
    }

?>