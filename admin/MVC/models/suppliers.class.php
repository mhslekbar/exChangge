<?php

class Suppliers extends Database {

    public function getSuppliers() {
        $stmt = $this->connect()->query("SELECT * FROM suppliers");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insertSupplier($fname,$butikName,$dette) {
        $stmt = $this->connect()->prepare("INSERT INTO suppliers (FullName,BoutiqueName,ssDette) VALUES (?,?,?) ");
        $stmt->execute([$fname,$butikName,$dette]);
        return $stmt->rowCount();
    }

    public function checkSupp($butikName) {
        $stmt = $this->connect()->prepare("SELECT * FROM suppliers WHERE BoutiqueName = ?");
        $stmt->execute([$butikName]);
        return $stmt->fetch();
    }

    public function getSupp($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateSupp($fname,$butikName,$id) {
        $stmt = $this->connect()->prepare("UPDATE suppliers SET FullName = ? , BoutiqueName = ? WHERE id = ?");
        $stmt->execute([$fname,$butikName,$id]);
        return $stmt->rowCount();
    }

    public function deleteSupp($id) {
        $stmt = $this->connect()->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function setDette($dette,$id) {
        $stmt = $this->connect()->prepare("UPDATE suppliers SET ssDette = ? WHERE id = ?");
        $stmt->execute([$dette,$id]);
        return $stmt->rowCount();
    }

    // Insert into zzPaySupplier 

    public function insertPaySupplier($idSupp,$amount) {
        $stmt = $this->connect()->prepare("INSERT INTO zzPaySupplier (ppidSupp,ppPay,ppType,date) VALUES (?,?,'retrait',now())");
        $stmt->execute([$idSupp,$amount]);
        return $stmt->rowCount();
    }

}

?>