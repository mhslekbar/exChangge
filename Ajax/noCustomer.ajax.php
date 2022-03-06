<?php
    include "connection.php";
    include "../includes/functions/functions.php";
    $do = isset($_GET['do']) ? htmlspecialchars($_GET['do']) : null;
    $branchReceipt = isset($_POST['branchReceipt']) ? htmlspecialchars($_POST['branchReceipt']) : null;
    $branchSender = isset($_POST['branchSender']) ? htmlspecialchars($_POST['branchSender']) : null;

    $searchReceipt = isset($_POST['searchReceipt']) ? htmlspecialchars($_POST['searchReceipt']) : null;
    
    $today = Date("Y-m-d");

    if($do == "getRateOfBrnch") {
        $currencyRR = getSymbol(getBrnch($branchReceipt)['bbCurrencyType'])['currencyRR'];
        echo getRates($branchSender,$currencyRR)['retail_price'] . " " . getRates($branchSender,$currencyRR)['cost_price'] . " " . $currencyRR;
    } 
    /*** SEARCH */
    else if($do == "getCustomerOnSearchReceipt" ) {
        foreach(getCustomerOnSearchReceipt($today,$searchReceipt,$branchReceipt) as $val):
            getNoCustomer($val);
        endforeach;
    } 
    
    else if($do == "getTransNoCustomersReceipt") {
        foreach(getTransNoCustomersReceipt($branchReceipt,$today) as $val):
            getNoCustomer($val);
        endforeach;
    }

    function getNoCustomer($val) {
        echo"<tr>
        <td>".$val['nnID']."</td>
        <td>".getBrnch($val['nnBranchSender'])['bbBrancheName']."</td>
        <td>".removeComma($val['nnAmountSend'])." " .getSymbol(getBrnch($val['nnBranchSender'])['bbCurrencyType'])['currencyRR']."</td>
        <td>".$val['nnAmountReceipt']. " " . getSymbol(getBrnch($val['nnBranchReceipt'])['bbCurrencyType'])['currencyRR']. "</td>
        <td>".$val['nnSenderContact']."</td>
        <td>".$val['nnReceiptName']."</td>
        <td>";
        echo $val['nnValider'] == 0 ? 
        '<button type="button" class="btn btn-warning btnValider" data-bs-toggle="modal" data-bs-target="#withDrwModal">
                        <i class="fas fa-hand-holding-usd"></i>
                        </button>' : 
                        "<span class='recu'>Oui</span>";
        echo "</td>
        <td>".$val['nnDate']."</td>
    </tr>";
    }

    function getBrnch($branchReceipt) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM branchs WHERE bbid = ?");
        $stmt->execute([$branchReceipt]);
        return $stmt->fetch();
    }

    function getSymbol($idRR) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM rates WHERE idRR = ?");
        $stmt->execute([$idRR]);
        return $stmt->fetch();
    }

    function getRates($idbrnch,$curr) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM ratebranchs WHERE idBranchRR = ? AND currencyRR = ?");
        $stmt->execute([$idbrnch,$curr]);
        return $stmt->fetch();
    }


    function getCustomerOnSearchReceipt($today,$searchReceipt,$branchReceipt) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM zznocustomers WHERE  (substr(nnDate,1,10) = ? OR nnValider = 0) AND  (nnSenderContact like ?) AND nnBranchReceipt = ?");
        $stmt->execute([$today,'%'.$searchReceipt.'%',$branchReceipt]);
        return $stmt->fetchAll();
    }

  
    function getTransNoCustomersReceipt($branchReceipt,$today) {
        global $conn;
        $stmt = $conn->prepare("SELECT DISTINCT(nnBranchSender), n.*,b.* FROM zznocustomers n JOIN branchs b ON nnBranchSender <> bbid AND nnBranchReceipt = bbid WHERE (nnBranchReceipt = ?) AND (substr(nnDate,'1','10') = ? OR nnValider = 0) ORDER BY nnID DESC");
        $stmt->execute([$branchReceipt,$today]);
        return $stmt->fetchAll();
    }


?>