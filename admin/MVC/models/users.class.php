<?php

class Users extends Database {
    
    // public function checkConn($usern) {
    //     $stmt = $this->connect()->prepare("SELECT * FROM zzusers WHERE uuUsername = ? AND");
    //     $stmt->execute([$usern]);
    //     return $stmt->fetch();
    // }

    public function getUsers() {
        $stmt = $this->connect()->query("SELECT * FROM zzusers");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function checkUser($user) {
        $stmt = $this->connect()->prepare("SELECT * FROM zzusers WHERE uuUsername = ?");
        $stmt->execute([$user]);
        return $stmt->fetch();
    }

    public function insertUser($user,$passHash,$name,$phone,$status) {
        $stmt = $this->connect()->prepare("INSERT INTO zzusers (uuUsername,uuPassword,uuFullName,uuCellphone,uuStatus) VALUES (?,?,?,?,?)");
        $stmt->execute([$user,$passHash,$name,$phone,$status]);
        return $stmt->rowCount();
    }

    public function getUser($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM zzusers WHERE uuid = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function checkUserForUpdate($user,$id) {
        $stmt = $this->connect()->prepare("SELECT * FROM zzusers WHERE uuUsername = ? AND uuid <> ?");
        $stmt->execute([$user,$id]);
        return $stmt->fetch();
    }    

    public function updateUser($user,$name,$phone,$status,$id) {
        $stmt = $this->connect()->prepare("UPDATE zzusers SET uuUsername = ?, uuFullName = ?, uuCellphone = ?, uuStatus = ? WHERE uuid = ?");
        $stmt->execute([$user,$name,$phone,$status,$id]);
        return $stmt->rowCount();
    }
  
    public function deleteUser($id) {
        $stmt = $this->connect()->prepare("DELETE FROM zzusers WHERE uuid = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function checkUserNotInBranch($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM branchs WHERE bbCaissier = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function setPassword($newPassHash,$id) {
        $stmt = $this->connect()->prepare("UPDATE zzusers SET uuPassword = ? WHERE uuid = ?");
        $stmt->execute([$newPassHash,$id]);
        return $stmt->rowCount();
    }

}

?>