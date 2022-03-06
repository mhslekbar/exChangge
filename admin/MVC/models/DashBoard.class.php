<?php 

class DashBoard extends Database {
    
    public function countRates() {
        $stmt = $this->connect()->query("SELECT * FROM rates");
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function countUsers() {
        $stmt = $this->connect()->query("SELECT * FROM zzusers");
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function countBranchs() {
        $stmt = $this->connect()->query("SELECT * FROM branchs");
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function countChargerBranch() {
        $stmt = $this->connect()->query("SELECT * FROM chargerBranch");
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function countSuppliers() {
        $stmt = $this->connect()->query("SELECT * FROM suppliers");
        $stmt->execute();
        return $stmt->rowCount();
    }
    public function countCustomers() {
        $stmt = $this->connect()->query("SELECT * FROM zzCustomers");
        $stmt->execute();
        return $stmt->rowCount();
    }
    
}

?>