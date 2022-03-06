<?php

class Rates extends Database {


    public function getRates() {
        $stmt = $this->connect()->query("SELECT * FROM rates");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function checkRate($name,$symbol) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE nameRR = ? OR currencyRR = ? ");
        $stmt->execute([$name,$symbol]);
        return $stmt->fetch();
    }
    
    public function checkRateForUpdate($name,$symbol,$id) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE (nameRR = ? OR currencyRR = ?) AND idRR <> ? ");
        $stmt->execute([$name,$symbol,$id]);
        return $stmt->fetch();
    }

    public function insertRate($name,$symbol,$cost,$retail) {
        $stmt = $this->connect()->prepare("INSERT INTO rates (nameRR,currencyRR,cost_price,retail_price,add_at,update_at) VALUES (?,?,?,?,now(),now())");
        $stmt->execute([$name,$symbol,$cost,$retail]);
        return $stmt->rowCount();
    }
    
    public function getRate($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM rates WHERE idRR = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateRate($name,$symbol,$cost,$sale,$id) {
        $stmt = $this->connect()->prepare("UPDATE rates SET nameRR = ? , currencyRR = ? , cost_price = ? , retail_price = ?, update_at = now() WHERE idRR = ? ");
        $stmt->execute([$name,$symbol,$cost,$sale,$id]);
        return $stmt->rowCount();
    }

    public function deleteRate($id) {
        $stmt = $this->connect()->prepare("DELETE FROM rates WHERE idRR = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

}

?>