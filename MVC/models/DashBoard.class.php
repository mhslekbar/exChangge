<?php 

class DashBoard extends Database {
    
    public function countTrans($idBrnch,$today) {
        $stmt = $this->connect()->prepare("SELECT * FROM zztransactions WHERE ttidBranch = ? AND substr(ttDate,'1','10') = ? ");
        $stmt->execute([$idBrnch,$today]);
        return $stmt->rowCount();
    }

    public function countCustomers($bbid) {
        $stmt = $this->connect()->prepare("SELECT * FROM zzCustomers WHERE ccAddress = ?");
        $stmt->execute([$bbid]);
        return $stmt->rowCount();
    }

    public function countTransferNoCust($idSender,$idReceipt,$today) {
        $stmt = $this->connect()->prepare("SELECT * FROM zznocustomers WHERE (nnBranchSender = ? OR nnBranchReceipt = ?) AND (substr(nnDate,'1','10') = ? OR nnValider = 0)");
        $stmt->execute([$idSender,$idReceipt,$today]);
        return $stmt->rowCount();
    }

    // FROM TransaCtion CLass

    public function getBrnch($idCaissier) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbCaissier = ?");
        $stmt->execute([$idCaissier]);
        return $stmt->fetch();
    }


}

?>