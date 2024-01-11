<?php

namespace CMS;

use Exception;

class User
{
    // Constructor and variable to initialize the User class with a Database object
    protected $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    // function to create an user in the database by 
    public function create(array $user)
    {
        // hashing their passwords
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        try {
            // checking that no email or username is duplicated
            $duplicates = [];
            $sql = "SELECT * FROM user WHERE username = :username";
            $result = $this->db->runSQL($sql, [$user['username']]);
            if ($result->rowCount() > 0) {
                array_push($duplicates, $user['username']);
            }
            $sql = "SELECT * FROM user WHERE email = :email";
            $result = $this->db->runSQL($sql, [$user['email']]);
            if ($result->rowCount() > 0) {
                array_push($duplicates, $user['email']);
            }
            //returning duplicates if there are
            if (count($duplicates) > 0) {
                return $duplicates;
            }
            // if query goes through return true
            $sql = "INSERT INTO user (image_url,first_name,last_name,username,email,password,description,date_joined)
                        VALUES (null,:first_name,:last_name,:username,:email,:password,null,NOW())";
            $this->db->runSQL($sql, $user);
            return true;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    // logging in by using the details provided
    public function login(string $email, string $password)
    {
        $sql = "SELECT id,image_url,first_name,last_name,username,email,password,description,date_joined 
            FROM user 
            WHERE email=:email";
        //  if no user is returned then return false
        $user = $this->db->runSQL($sql, [$email])->fetch();
        if (!$user) {
            return false;
        }
        //making sure password matches
        $authenticated = password_verify($password, $user['password']);
        return ($authenticated ? $user : false);
    }
    //getting one user from the database that matches id
    public function getOne(string $id)
    {
        $sql = "SELECT id,image_url,first_name,last_name,username,email,password,description,date_joined 
            FROM user 
            WHERE id=:id";
        return $this->db->runSQL($sql, [$id])->fetch();
    }
    // getting the id of the user by sypplting email
    public function getUserId(string $email)
    {
        $sql = "SELECT id FROM user WHERE email=:email ";
        $user = $this->db->runSQL($sql, [$email])->fetch();
        return $user['id'];
    }
    // adding an image by supplying an id and imageUrl
    public function addImage(string $id, string $imageUrl)
    {
        try {
            $sql = "UPDATE user SET image_url = :imageUrl WHERE id = :id";
            $this->db->runSQL($sql, ['id' => $id, 'imageUrl' => $imageUrl]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    // same idea as above with description
    public function addDescription(string $id, string $description)
    {
        try {
            $sql = "UPDATE user SET description = :description WHERE id = :id";
            $this->db->runSQL($sql, ['id' => $id, 'description' => $description]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    // same idea as above with email
    public function editEmail($id, $email)
    {
        try {
            $sql = "UPDATE user SET email = :email WHERE id = :id";
            $this->db->runSQL($sql, ['id' => $id, 'email' => $email]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    // same idea as above with username
    public function editUsername($id, $username)
    {
        try {
            $sql = "UPDATE user SET username = :username WHERE id = :id";
            $this->db->runSQL($sql, ['id' => $id, 'username' => $username]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    // deleting account that matches the id
    public function deleteAccount($id)
    {
        try {
            $sql = "DELETE FROM user WHERE id = :id";
            $this->db->runSQL($sql, ['id' => $id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
