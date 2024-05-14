<?php
class database
{
    function opencon()
    {
        return new PDO('mysql:host=localhost;dbname=phpoop221','root','');
    }

    function check($username,$password){
        $con = $this->opencon();
        $query = "SELECT * FROM users WHERE user='".$username. "'&&pass='".$password."'";
        return $con->query($query)->fetch();
    }

    function signup($username,$password,$firstname,$lastname,$birthday,$sex){
        $con = $this->opencon();
        $query = $con->prepare("SELECT user FROM users WHERE user = ?");
        $query->execute([$username]);
        $existingUser = $query->fetch();

        if($existingUser){	
            return false;
    }
    return $con->prepare("INSERT INTO users (user,pass,first_name,last_name,sex,birthdate) VALUES (?,?,?,?,?,?)")
    -> execute([$username,$password,$firstname,$lastname,$sex,$birthday]);
           
}
    function signupUser($firstname, $lastname, $birthday, $sex, $username,$password){
        $con = $this->opencon();
        $query = $con->prepare("SELECT user FROM users WHERE user = ?");
        $query->execute([$username]);
        $existingUser = $query->fetch();

        if($existingUser){	
            return false;
    }
         $con->prepare("INSERT INTO users (first_name, last_name, birthdate, sex, user,pass) VALUES (?,?,?,?,?,?)")
            -> execute([$firstname, $lastname, $birthday, $sex, $username, $password]);

        return $con->lastInsertId();
}

    function insertAddress($user_id, $street, $barangay, $city, $province){
        $con = $this->opencon();
       
        //$query = $con->prepare("SELECT user FROM users WHERE user = ?");

        return $con->prepare("INSERT INTO user_address (user_id, user_street, user_barangay,user_city,user_province) VALUES (?,?,?,?,?)")
        -> execute([$user_id,$street, $barangay, $city, $province]);
    }

    function view(){
        $con = $this->opencon();
        return $con->query("SELECT
        users.user_id,users.first_name, users.last_name,users.birthdate,users.sex,users.user,CONCAT(user_address.user_street, ' ', user_address.user_barangay,' ', user_address.user_city,' ', user_address.user_province) AS Address FROM user_address INNER JOIN users ON user_address.user_id = users.user_id")->fetchAll();
    }

    function delete($id ){
        try{
            $con = $this->opencon();
            $con->beginTransaction();

            $query = $con->prepare("DELETE FROM user_address WHERE user_id = ?");
            $query->execute([$id]);

            $query2 = $con->prepare("DELETE FROM users WHERE user_id = ?");
            $query2->execute([$id]);

            $con->commit();
            return true;

        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }

    function viewdata($id){
        try{
            $con = $this->opencon();
            $query=$con->prepare("SELECT
            users.user_id, users.first_name, users.last_name, users.birthdate, users.sex, users.user, users.pass, user_address.user_street, user_address.user_barangay, user_address.user_city, user_address.user_province FROM user_address INNER JOIN users ON user_address.user_id = users.user_id WHERE users.user_id = ?");
            $query->execute([$id]);
            return $query->Fetch();
            }
        catch (PDOException $e) {
            return [];
    }
}


    function updateUser($id, $firstname, $lastname, $birthday, $sex, $user, $password){

    function updateUser($id, $firstname, $lastname, $birthday, $sex, $username, $password){

        try{
        $con = $this->opencon();
        $con->beginTransaction();
        $query = $con->prepare("UPDATE users SET first_name=?, last_name=?, birthdate=?, sex=?, user=?, pass=? WHERE user_id =?");

        $query->execute([$firstname, $lastname, $birthday, $sex, $user,$password,$id]);

        $query->execute([$firstname, $lastname, $birthday, $sex, $username, $password, $id]);

        $con->commit();
        return true;
    } catch(PDOException $e) {
        $con->rollBack();
            return false;
    }
}


function updateUserAddress($id,$street,$barangay,$city,$province){
function updateUserAddress($id, $street, $barangay, $city, $province){
    try{
    $con = $this->opencon();
    $query = $con->prepare("UPDATE user_address SET user_street=?, user_barangay=?, user_city=?, user_province=? WHERE user_id =?");
    return $query->execute([$street,$barangay,$city,$province,$id]);

    }   catch(PDOException $e) {
        $con->rollBack();
            return false;
    }
}
}
    }
}