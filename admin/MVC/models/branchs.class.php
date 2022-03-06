<?php

class Branchs extends Database {

    public function getBranches() {
        $stmt = $this->connect()->query("SELECT * FROM branchs b JOIN rates r JOIN zzUsers u ON b.bbCurrencyType = r.idRR AND u.uuid = b.bbCaissier");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function checkBranch($name) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbBrancheName = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function checkBranchAndNotSameid($name,$id) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbBrancheName = ? AND bbid <> ?");
        $stmt->execute([$name,$id]);
        return $stmt->fetch();
    }

    public function checkCaissierBranch($caissier) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbCaissier = ?");
        $stmt->execute([$caissier]);
        return $stmt->fetch();
    }

    public function checkCaissierBranchAndNotSameid($caissier,$id) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbCaissier = ? AND bbid <> ?");
        $stmt->execute([$caissier,$id]);
        return $stmt->fetch();
    }

    public function getCashier() {
        $stmt = $this->connect()->query("SELECT * FROM zzUsers WHERE uuStatus = 0");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insertBranch($name,$caissier,$location,$solde,$devise) {
        $stmt = $this->connect()->prepare("INSERT INTO branchs (bbBrancheName,bbCaissier,bbLocation,bbBalance,bbCurrencyType,bbDate) VALUES (?,?,?,?,?,now())");
        $stmt->execute([$name,$caissier,$location,$solde,$devise]);
        return $stmt->rowCount();
    }

    public function getBranch($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs u JOIN rates r ON u.bbCurrencyType = r.idRR WHERE bbid = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateBranch($name,$caissier,$location,$solde,$devise,$id) {
        $stmt = $this->connect()->prepare("UPDATE branchs SET bbBrancheName = ?, bbCaissier = ?, bbLocation = ? ,bbBalance = ?, bbCurrencyType = ? WHERE bbid = ?");
        $stmt->execute([$name,$caissier,$location,$solde,$devise,$id]);
        return $stmt->rowCount();
    }

    public function deleteBranch($id) {
        $stmt = $this->connect()->prepare("DELETE FROM branchs WHERE bbid = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

}


?>