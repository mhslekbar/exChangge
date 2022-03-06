<?php

class Customers extends Database {

    public function getCustomers() {
        $stmt = $this->connect()->query("SELECT * FROM zzcustomers c JOIN zzusers u ON c.ccAddBy = u.uuid");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCustomer($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM zzcustomers WHERE ccID = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function checkCustomer($phone,$carteid) {
        $stmt = $this->connect()->prepare("SELECT * FROM zzcustomers WHERE ccCellphone = ? OR ccCarteID = ?");
        $stmt->execute([$phone,$carteid]);
        return $stmt->rowCount();
    }

    public function insertCustomers($fname,$phone,$carteid,$addr,$solde,$usrid) {
        $stmt = $this->connect()->prepare("INSERT INTO zzCustomers (ccFullName,ccCellphone,ccCarteID,ccAddress,ccSolde,ccAddBy,ccApprove,ccAddAt) VALUES (?,?,?,?,?,?,1,now()) ");
        $stmt->execute([$fname,$phone,$carteid,$addr,$solde,$usrid]);
        return $stmt->rowCount();
    }

    /**  START FROM BRANCHS TABLE */

    public function getLocations() {
        $stmt = $this->connect()->query("SELECT * FROM branchs");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getLoc($loc) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbid = ?");
        $stmt->execute([$loc]);
        return $stmt->fetch();
    }

    public function getBranchWHereLoc($addr) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbid = ? ");
        $stmt->execute([$addr]);
        return $stmt->fetch();
    }

    public function updateBranch($balance,$addr) {
        $stmt = $this->connect()->prepare("UPDATE branchs SET bbBalance = ? WHERE bbid = ?");
        $stmt->execute([$balance,$addr]);
        return $stmt->rowCount();
    }

    public function getCurrencyFromRates($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE idRR =  ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getSoldeOfBranchWhereAddr($addr) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbLocation = ?");
        $stmt->execute([$addr]);
        return $stmt->fetch();
    }


    /**  END FROM BRANCHS TABLE */

    public function approveCustomer($id) {
        $stmt = $this->connect()->prepare("UPDATE zzCustomers SET ccApprove = 1 WHERE ccID = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function desApproveCustomer($id) {
        $stmt = $this->connect()->prepare("UPDATE zzCustomers SET ccApprove = 0 WHERE ccID = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function checkCustomerNotSameID($phone,$carteid,$id) {
        $stmt = $this->connect()->prepare("SELECT * FROM zzcustomers WHERE (ccCellphone = ? OR ccCarteID = ?) AND ccID <> ?");
        $stmt->execute([$phone,$carteid,$id]);
        return $stmt->rowCount();
    }

    public function updateCustomer($fname,$phone,$carteid,$usrid,$id) {
        $stmt = $this->connect()->prepare("UPDATE zzCustomers SET ccFullName = ?, ccCellphone = ?, ccCarteID = ?, ccUpdateAt = now(),ccAddBy = ? WHERE ccID = ?");
        $stmt->execute([$fname,$phone,$carteid,$usrid,$id]);
        return $stmt->rowCount();
    }

    public function deleteCustomer($id) {
        $stmt = $this->connect()->prepare("DELETE FROM zzCustomers WHERE ccID = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    // STatistiques OF Customers
    // From TransCustomer Table
    public function getTransCusts($idCust) {
        $stmt = $this->connect()->prepare("SELECT * FROM zztranscustomer WHERE tcIdCust = ? ORDER BY tcIdCust DESC");
        $stmt->execute([$idCust]);
        return $stmt->fetchAll();
    }

    public function getBranch($loc) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbid = ?");
        $stmt->execute([$loc]);
        return $stmt->fetch();    
    }

    public function getRate($Curr) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE idRR = ?");
        $stmt->execute([$Curr]);
        return $stmt->fetch();
    }

}


?>