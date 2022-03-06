<?php
    require "connection.php"; 
    $do = isset($_GET['do']) ? htmlspecialchars($_GET['do']) : null;
    $idBrnch    = isset($_POST['idBrnch']) ? htmlspecialchars($_POST['idBrnch']) : null;
    $tomorrow  = isset($_POST['tomorrow']) ? htmlspecialchars($_POST['tomorrow']) : null;
    $today      = isset($_POST['today']) ? htmlspecialchars($_POST['today']) : null;

    if($do == "getBenefOfBrnch") {
        echo sumBenefOfTransExchange($idBrnch,$today,$tomorrow)['sum'] . " " . sumBenefOfnoCustomer($idBrnch,$today,$tomorrow)['sum'] ." ". getDevise(getidCurrency($idBrnch)['bbCurrencyType'])['retail_price'];
    } else if($do == "getBenefOfAllBrnch") {
        $totTrans = 0;                                     
        $totOfnoCust = 0; 

        foreach(sumBenefOfTransExchangeOfAll($today,$tomorrow) as $benefArr) {
            $sum        = $benefArr['sum'] ?? null;
            $brnchid    = $benefArr['ttidBranch'] ?? null;
            if($brnchid != null){
                $idRR       = getidCurrency($brnchid)['bbCurrencyType'];
                $rt_price   = getDevise($idRR)['retail_price'];
                $totTrans  += $sum * $rt_price;    
            }
        }
        
        foreach(sumBenefOfnoCustomerOfAll($today,$tomorrow) as $benefArr) {
            $sum        = $benefArr['sum'] ?? null;
            $brnchid    = $benefArr['nnBranchSender'] ?? null;
            if($brnchid != null) {
                $idRR       = getidCurrency($brnchid)['bbCurrencyType'];
                $rt_price   = getDevise($idRR)['retail_price'];
                $totOfnoCust += $sum * $rt_price;
            }     
        }
        echo $totTrans . " " . $totOfnoCust;
    }

    function sumBenefOfnoCustomer($idBrnch,$today,$tomorrow) {
        global $conn;
        $stmt = $conn->prepare("SELECT SUM(nnBenef) as sum FROM zznocustomers WHERE nnBranchSender = ? AND nnDate between ? AND ?");
        $stmt->execute([$idBrnch,$today,$tomorrow]);
        return $stmt->fetch();
    }

    function sumBenefOfTransExchange($idBrnch,$today,$tomorrow) {
        global $conn;
        $stmt = $conn->prepare("SELECT SUM(ttBenef) as sum FROM zztransactions WHERE ttidBranch = ? AND ttDate between ? AND ? ");
        $stmt->execute([$idBrnch,$today,$tomorrow]);
        return $stmt->fetch();
    }

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


    function sumBenefOfnoCustomerOfAll($today,$tomorrow) {
        global $conn;
        $stmt = $conn->prepare("SELECT nnBranchSender, SUM(nnBenef) as sum FROM zznocustomers WHERE nnDate between ? AND ? GROUP BY nnBranchSender");
        $stmt->execute([$today,$tomorrow]);
        return $stmt->fetchAll();
    }

    function sumBenefOfTransExchangeOfAll($today,$tomorrow) {
        global $conn;
        $stmt = $conn->prepare("SELECT ttidBranch, SUM(ttBenef) as sum FROM zztransactions WHERE ttDate between ? AND ? GROUP BY ttidBranch");
        $stmt->execute([$today,$tomorrow]);
        return $stmt->fetchAll();
    }
?>