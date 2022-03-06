<?php

class ChargerBranch extends Database {    
    
    public function getChargeBranchs() {
        $stmt = $this->connect()->prepare("SELECT *,cb.id as idChargBrnch  FROM chargerbranch cb JOIN branchs b JOIN suppliers s ON b.bbid = cb.idBranch AND cb.idSupp = s.id");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCurrencyRR($bbCurrencyType) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE idRR = ? ");
        $stmt->execute([$bbCurrencyType]);
        return $stmt->fetch();
    }

    public function suppliers() {
        $stmt = $this->connect()->query("SELECT * FROM suppliers");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function branchs() {
        $stmt = $this->connect()->query("SELECT * FROM branchs");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insertChargeBranch($supplier ,$branch, $amountDevise, $amountMRU, $pay) {
        $stmt = $this->connect()->prepare("INSERT INTO chargerbranch (idSupp, idBranch,amountDevise,amountMRU,amountPaye,add_at) VALUES (?,?,?,?,?,now()) ");
        $stmt->execute([$supplier ,$branch, $amountDevise, $amountMRU, $pay]);
        return $stmt->rowCount();
    }

    public function getBranch($branch) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbid = ?");
        $stmt->execute([$branch]);
        return $stmt->fetch();
    }

    public function getIDBranch($branchName) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbBrancheName = ?");
        $stmt->execute([$branchName]);
        return $stmt->fetch();
    }

    public function updateBranchBalance($newBalance,$branch) {
        $stmt = $this->connect()->prepare("UPDATE branchs SET bbBalance = ? WHERE bbid = ?");
        $stmt->execute([$newBalance,$branch]);
        return $stmt->rowCount();
    }

    public function getFourn($supplier) {
        $stmt = $this->connect()->prepare("SELECT * FROM suppliers WHERE id = ?");
        $stmt->execute([$supplier]);
        return $stmt->fetch();
    }
    
    public function getIDFourn($butikName) {
        $stmt = $this->connect()->prepare("SELECT * FROM suppliers WHERE BoutiqueName = ?");
        $stmt->execute([$butikName]);
        return $stmt->fetch();
    }
    
    public function updateSupplierDette($newDette,$supplier) {
        $stmt = $this->connect()->prepare("UPDATE suppliers SET ssDette = ? WHERE id = ?");
        $stmt->execute([$newDette,$supplier]);
        return $stmt->rowCount();
    }


    // Delete Charge Branch
    public function deleteChargeBrnch($id) {
        $stmt = $this->connect()->prepare("DELETE FROM chargerBranch WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function insertInPaySupplier($supplier,$pay) {
        $stmt = $this->connect()->prepare("INSERT INTO zzpaysupplier (ppidSupp,ppPay,ppType,date) VALUES (?,?,'payer',now())");
        $stmt->execute([$supplier,$pay]);
        return $stmt->rowCount();
    }
}

?>