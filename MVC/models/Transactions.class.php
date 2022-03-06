<?php

class Transactions extends Database {

    public function getBrnch($idCaissier) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbCaissier = ?");
        $stmt->execute([$idCaissier]);
        return $stmt->fetch();
    }

    public function getRate($typeCurr) {
        $stmt = $this->connect()->prepare("SELECT * FROM ratebranchs WHERE idBranchRR = ?");
        $stmt->execute([$typeCurr]);
        return $stmt->fetch();
    }

    public function getidOfSymbol($idBranch,$CurrName) {
        $stmt = $this->connect()->prepare("SELECT * FROM ratebranchs WHERE idBranchRR = ? AND currencyRR = ?");
        $stmt->execute([$idBranch,$CurrName]);
        return $stmt->fetch();
    }

    public function getCurrency($idBranch,$CurrName) {
        $stmt = $this->connect()->prepare("SELECT * FROM ratebranchs WHERE idBranchRR = ? AND currencyRR <> ?");
        $stmt->execute([$idBranch,$CurrName]);
        return $stmt->fetchAll();
    }

    public function getSymbol($idRR) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE idRR = ?");
        $stmt->execute([$idRR]);
        return $stmt->fetch();
    }

    public function getSymbolFromRateBranchs($idBranch,$idCurr) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs b JOIN rates r JOIN zzUsers u ON b.bbCurrencyType = r.idRR AND u.uuid = b.bbCaissier WHERE bbid = ? AND bbCurrencyType = ?");
        $stmt->execute([$idBranch,$idCurr]);
        return $stmt->fetch();
    }    

    public function insertTrans($idBranch, $fromCurr, $toCurr, $amountMain, $amountConvert, $amountBenef, $type) {
        $stmt = $this->connect()->prepare("INSERT INTO zzTransactions (ttidBranch,ttFromCurrency,ttToCurrency,ttMontant,ttNetConvert,ttBenef,ttType,ttDate) VALUES (?,?,?,?,?,?,?,now()) ");
        $stmt->execute([$idBranch, $fromCurr, $toCurr, $amountMain, $amountConvert, $amountBenef, $type]);
        return $stmt->rowCount();
    }

    public function getTrans($idBranch,$today=null) {
        if($today == null){
            $stmt = $this->connect()->prepare("SELECT t.* FROM zzTransactions t JOIN branchs b ON ttidBranch = bbid  WHERE ttidBranch = ?");
            $stmt->execute([$idBranch]);
        }else {
            $stmt = $this->connect()->prepare("SELECT t.* FROM zzTransactions t JOIN branchs b ON ttidBranch = bbid  WHERE ttidBranch = ? AND substr(ttDate,'1','10') = ?");
            $stmt->execute([$idBranch,$today]);    
        }
        return $stmt->fetchAll();
    }

    public function updateBranch($newBalance,$idBranch) {
        $stmt = $this->connect()->prepare("UPDATE branchs SET bbBalance = ? WHERE bbid = ?");
        $stmt->execute([$newBalance,$idBranch]);
        return $stmt->rowCount();
    }

    public function deleteTrans($id) {
        $stmt = $this->connect()->prepare("DELETE FROM zztransactions WHERE ttID = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function getRateBrnchWheridRR($typeCurr) {
        $stmt = $this->connect()->prepare("SELECT * FROM ratebranchs WHERE idRR = ?");
        $stmt->execute([$typeCurr]);
        return $stmt->fetch();
    }

    // For Admin Folder

    public function getUserWhereBrnch($idBranch) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbid = ?");
        $stmt->execute([$idBranch]);
        return $stmt->fetch();
    }



}

?>