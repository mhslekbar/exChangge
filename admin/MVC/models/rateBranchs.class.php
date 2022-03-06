<?php

class RateBranchs extends Database {


    public function getRateBranchs($branchid) {
        $stmt = $this->connect()->prepare("SELECT * FROM ratebranchs WHERE idBranchRR = ?");
        $stmt->execute([$branchid]);
        return $stmt->fetchAll();
    }

    public function getBranch($branchid) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbid = ?");
        $stmt->execute([$branchid]);
        return $stmt->fetch();
    }

    public function checkRate($name,$symbol,$branchid) {
        $stmt = $this->connect()->prepare("SELECT * FROM ratebranchs WHERE (nameRR = ? OR currencyRR = ?)  AND idBranchRR = ?");
        $stmt->execute([$name,$symbol,$branchid]);
        return $stmt->fetch();
    }
    
    public function checkRateForUpdate($name,$symbol,$branchid,$id) {
        $stmt = $this->connect()->prepare("SELECT * FROM ratebranchs WHERE (nameRR = ? OR currencyRR = ?)  AND idBranchRR = ? AND idRR <> ?");
        $stmt->execute([$name,$symbol,$branchid,$id]);
        return $stmt->fetch();
    }

    public function insertRate($branchid,$name,$symbol,$cost,$retail) {
        $stmt = $this->connect()->prepare("INSERT INTO rateBranchs (idBranchRR,nameRR,currencyRR,cost_price,retail_price,add_at,update_at) VALUES (?,?,?,?,?,now(),now())");
        $stmt->execute([$branchid,$name,$symbol,$cost,$retail]);
        return $stmt->rowCount();
    }
    
    public function getRate($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM rateBranchs WHERE idRR = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateRate($name,$symbol,$cost,$sale,$id) {
        $stmt = $this->connect()->prepare("UPDATE rateBranchs SET nameRR = ? , currencyRR = ? , cost_price = ? , retail_price = ?, update_at = now() WHERE idRR = ? ");
        $stmt->execute([$name,$symbol,$cost,$sale,$id]);
        return $stmt->rowCount();
    }

    public function deleteRate($id) {
        $stmt = $this->connect()->prepare("DELETE FROM rateBranchs WHERE idRR = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

}

?>