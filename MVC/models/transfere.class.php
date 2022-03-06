<?php

class Transfere extends Database {

    public function getBrnch($idCaissier) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbCaissier = ?");
        $stmt->execute([$idCaissier]);
        return $stmt->fetch();
    }

    public function updateBranchBalance($solde,$bbid) {
        $stmt = $this->connect()->prepare("UPDATE branchs SET bbBalance = ? WHERE bbid = ?");
        $stmt->execute([$solde,$bbid]);
        return $stmt->rowCount();
    }


    public function insertTransCust($idCust,$phone,$fname,$montant,$type) {
        $stmt = $this->connect()->prepare("INSERT INTO zztranscustomer (tcIdCust,tcPhone,tcFullName,tcAmount,tcType,tcDate) VALUES (?,?,?,?,?,now()) ");
        $stmt->execute([$idCust,$phone,$fname,$montant,$type]);
        return $stmt->rowCount();
    }

    public function getTransCust($today) {
        $stmt = $this->connect()->prepare("SELECT * FROM zztranscustomer tc JOIN zzcustomers c ON tc.tcIdCust = c.ccID WHERE substr(tcDate,'1','10') = ? ORDER BY tcID DESC");
        $stmt->execute([$today]);
        return $stmt->fetchAll();
    }
    

    public function getRateWhereidRR($currencyType) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE idRR = ?");
        $stmt->execute([$currencyType]);
        return $stmt->fetch();
    }


    
    /**   START Customers No Approve  */
    
    public function getBranchs($bbid) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbid <> ?");
        $stmt->execute([$bbid]);
        return $stmt->fetchAll();
    }
    
    public function getSymbol($idRR) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE idRR = ?");
        $stmt->execute([$idRR]);
        return $stmt->fetch();
    }
  
    public function insertRecord($branchSender, $contactSender, $branchReceipt, $contactReceipt, $nameReceipt, $amountSender, $amountReceipt, $Benef, $type) {
        $stmt = $this->connect()->prepare("INSERT INTO zznocustomers (nnBranchSender, nnSenderContact, nnBranchReceipt, nnReceiptContact, nnReceiptName, nnAmountSend, nnAmountReceipt, nnBenef, nnType, nnValider, nnDate) VALUES (?,?,?,?,?,?,?,?,?,0,now())  ");
        $stmt->execute([$branchSender, $contactSender, $branchReceipt, $contactReceipt, $nameReceipt, $amountSender, $amountReceipt, $Benef, $type]);
        return $stmt->rowCount();
    }

    //  Get Sended Transaction

    public function getTransNoCustomers($branchSender,$today) {
        $stmt = $this->connect()->prepare("SELECT DISTINCT(nnBranchReceipt), n.*,b.* FROM zznocustomers n JOIN branchs b ON nnBranchSender = bbid AND nnBranchReceipt <> bbid WHERE (nnBranchSender = ?) AND (substr(nnDate,'1','10') = ? OR nnValider = 0) ORDER BY nnID DESC");
        $stmt->execute([$branchSender,$today]);
        return $stmt->fetchAll();
    }

    // Get Receipt Transaction

    public function getTransNoCustomersReceipt($branchReceipt,$today) {
        $stmt = $this->connect()->prepare("SELECT DISTINCT(nnBranchSender), n.*,b.* FROM zznocustomers n JOIN branchs b ON nnBranchSender <> bbid AND nnBranchReceipt = bbid WHERE (nnBranchReceipt = ?) AND (substr(nnDate,'1','10') = ? OR nnValider = 0) ORDER BY nnID DESC");
        $stmt->execute([$branchReceipt,$today]);
        return $stmt->fetchAll();
    }
    

    public function branch($bbid) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbid = ?");
        $stmt->execute([$bbid]);
        return $stmt->fetch();
    }

    public function getBranchWHereName($bname) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbBrancheName = ?");
        $stmt->execute([$bname]);
        return $stmt->fetch();
    }

    public function getNoCust($contactReceipt) {
        $stmt = $this->connect()->prepare("SELECT * FROM zznocustomers WHERE nnReceiptContact = ?");
        $stmt->execute([$contactReceipt]);
        return $stmt->fetch();
    }

    public function updateValider($id) {
        $stmt = $this->connect()->prepare("UPDATE zznocustomers SET nnValider = 1 WHERE nnID = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }  

    
    /**   END Customers No Approve  */

}


?>