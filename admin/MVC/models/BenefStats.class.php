<?php

class BenefStats extends Database {

    public function getAllBranchs() {
        $stmt = $this->connect()->query("SELECT * FROM branchs");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function sumBenefOfTransExchange($today,$tomorrow) {
        $stmt = $this->connect()->prepare("SELECT ttidBranch, SUM(ttBenef) as sum FROM zztransactions WHERE ttDate between ? AND ? GROUP BY ttidBranch");
        $stmt->execute([$today,$tomorrow]);
        return $stmt->fetchAll();
    }

    public function sumBenefOfnoCustomer($today,$tomorrow) {
        $stmt = $this->connect()->prepare("SELECT nnBranchSender, sum(nnBenef) as sum FROM `zznocustomers` WHERE nnDate between ? AND ? GROUP BY nnBranchSender");
        $stmt->execute([$today,$tomorrow]);
        return $stmt->fetchAll();
    }

    public function getBrnch($brnchid) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbid = ?");
        $stmt->execute([$brnchid]);
        return $stmt->fetch();
    }

    public function getDevise($idRR) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE idRR = ? ");
        $stmt->execute([$idRR]);
        return $stmt->fetch();
    }

}


?>