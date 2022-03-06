<?php

class Customers extends Database {

    public function getBrnch($idCaissier) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbCaissier = ?");
        $stmt->execute([$idCaissier]);
        return $stmt->fetch();
    }

    public function getCustomer($phone) {
        $stmt = $this->connect()->prepare("SELECT * FROM zzcustomers WHERE ccCellphone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }

    public function checkCustomer($phone) {
        $stmt = $this->connect()->prepare("SELECT * FROM zzcustomers WHERE ccCellphone = ? AND ccApprove = 0");
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }

    public function updateBranchBalance($solde,$bbid) {
        $stmt = $this->connect()->prepare("UPDATE branchs SET bbBalance = ? WHERE bbid = ?");
        $stmt->execute([$solde,$bbid]);
        return $stmt->rowCount();
    }

    public function updateCustomerSolde($solde,$phone) {
        $stmt = $this->connect()->prepare("UPDATE zzcustomers SET ccSolde = ? WHERE ccCellphone = ?");
        $stmt->execute([$solde,$phone]);
        return $stmt->rowCount();
    }

    public function insertTransCust($idCust,$phone,$fname,$montant,$type) {
        $stmt = $this->connect()->prepare("INSERT INTO zztranscustomer (tcIdCust,tcPhone,tcFullName,tcAmount,tcType,tcDate) VALUES (?,?,?,?,?,now()) ");
        $stmt->execute([$idCust,$phone,$fname,$montant,$type]);
        return $stmt->rowCount();
    }

    public function getTransCust($today) {
        $stmt = $this->connect()->prepare("SELECT * FROM zztranscustomer tc JOIN zzcustomers c ON tc.tcIdCust = c.ccID WHERE substr(tcDate,'1','10') = ? ORDER BY tcID  DESC");
        $stmt->execute([$today]);
        return $stmt->fetchAll();
    }
    

    public function getRateWhereidRR($currencyType) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE idRR = ?");
        $stmt->execute([$currencyType]);
        return $stmt->fetch();
    }

    public function countApproveClient() {
        $stmt = $this->connect()->query("SELECT * FROM zzCustomers WHERE ccApprove = 1");
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function countNoApproveClient($today) {
        $stmt = $this->connect()->prepare("SELECT * FROM zznocustomers WHERE substr(nnDate,1,10) = ? OR nnValider = 0");
        $stmt->execute([$today]);
        return $stmt->rowCount();
    }

    

}


?>