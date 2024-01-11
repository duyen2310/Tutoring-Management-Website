<?php

namespace CMS;

class Lp
{
    protected $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    // used for getting an lp
    public function get(string $id)
    {
        $sql = "SELECT lp.id, lp.user_id, u.username,lp.user_id, lp.copied_member_id as copied_user_id, lp.thumbnail, lp.title, lp.description, lp.date_created, lp.date_edited, l.id as language_id, l.language, c.name,c.id as c_id
            FROM lp lp 
            JOIN user u ON u.id = lp.user_id
            JOIN language l ON l.id = lp.language_id 
            JOIN category c ON lp.category_id = c.id   
            WHERE lp.id=:id";
    
        return $this->db->runSQL($sql, [$id])->fetch();
    }
    // used for getting copied lp (you get user id and copied user id instead)
    public function getCopy(string $id){

            $sql = "SELECT lp.id, lp.copied_member_id as cid,u.username,cu.username as cusername, lp.user_id, lp.thumbnail, lp.title, lp.description, lp.date_created, lp.date_edited,l.id as language_id,l.language,c.name,c.id as c_id
            FROM lp
            JOIN user u ON u.id = lp.user_id
            JOIN user cu ON cu.id = lp.copied_member_id
            join language l on l.id = lp.language_id
            join category c on lp.category_id = c.id
            WHERE lp.id = :id;
            ";
        
            return $this->db->runSQL($sql, [$id])->fetch();
    }
    // getting all lp's with supplied id
    public function getAllId(int $id)
    {
        $sql = "SELECT lp.id,lp.user_id,u.username,lp.thumbnail,lp.title,lp.description,lp.date_created,lp.date_edited,l.id as language_id,l.language,c.name,c.id as c_id
            from lp lp 
            JOIN user u ON u.id = lp.user_id 
            JOIN language l ON l.id = lp.language_id 
            JOIN category c ON lc.category_id = c.id   
            WHERE lp.id=:id";
        return $this->db->runSQL($sql, [$id])->fetchAll();
    }
    // getting all lp's
    public function getAll(): array
    {
        $sql = "SELECT lp.id,lp.user_id,u.username,lp.thumbnail,lp.title,lp.description,lp.date_created,lp.date_edited,l.id as language_id,l.language,c.name as category
            from lp lp 
            JOIN user u ON u.id = lp.user_id 
            JOIN language l ON l.id = lp.language_id 
            JOIN category c ON lp.category_id = c.id";
        return $this->db->runSQL($sql)->fetchAll();
    }
    // getting all lp's from one user (used in profile page)
    public function getAllOfUser( $id)
    {
        $sql = "SELECT lp.id,lp.user_id,u.username,lp.thumbnail,lp.title,lp.description,lp.date_created,lp.date_edited,l.id as language_id,l.language,c.name as category
            from lp lp 
            JOIN user u ON u.id = lp.user_id 
            JOIN language l ON l.id = lp.language_id 
            JOIN category c ON lp.category_id = c.id   
            WHERE lp.user_id = :id";
        return $this->db->runSQL($sql, [$id])->fetchAll();
    }


    // creating a lp and returning the last inserted id
    public function createLp(string $title,string $userId,$language_id,$selectedCategory){
        $sql = "INSERT INTO
        lp (user_id, thumbnail, title, date_created, date_edited, language_id,category_id) 
        VALUES (:user_id,null,:title,NOW(),null,:language_id,:category_id)";
        $this->db->runSQL($sql, [':user_id' => $userId,':title'=>$title,':language_id'=>$language_id,'category_id'=>$selectedCategory]);
        return $this->db->lastInsertId();
    }
    // adding and updating values how we did in user
    public function addDescription(int $lpId, string $description)
    {
        try {
            $sql = "UPDATE lp SET description = :description WHERE id = :lpId";
            $this->db->runSQL($sql, [':description' => $description, ':lpId' => $lpId]);
            return true;
        } catch (\PDOException $e) {
            return false; 
        }
    }
    public function addThumbnail(int $lpId, string $thumbnail)
    {
        try {
            $sql = "UPDATE lp SET thumbnail = :thumbnail WHERE id = :lpId";
            $this->db->runSQL($sql, [':thumbnail' => $thumbnail, ':lpId' => $lpId]);
            return true;
        } catch (\PDOException $e) {
            return false; 
        }
    }
    public function updateTitle(string $lpId, string $title)
    {
        try {
            $sql = "UPDATE lp SET title = :title, date_edited = NOW() WHERE id = :lpId";
            $this->db->runSQL($sql, [':title' => $title, ':lpId' => $lpId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function editThumbnail(string $lpId, string $thumbnail)
    {
        try {
            $sql = "UPDATE lp SET thumbnail = :thumbnail, date_edited = NOW() WHERE id = :lpId";
            $this->db->runSQL($sql, [':thumbnail' => $thumbnail, ':lpId' => $lpId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function updateCategory(string $lpId, int $category)
    {
        try {
            $sql = "UPDATE lp SET category_id = :category, date_edited = NOW() WHERE id = :lpId";
            $this->db->runSQL($sql, [':category' => $category, ':lpId' => $lpId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function updateLanguage(string $lpId, int $language)
    {
        try {
            $sql = "UPDATE lp SET language_id = :language, date_edited = NOW() WHERE id = :lpId";
            $this->db->runSQL($sql, [':language' => $language, ':lpId' => $lpId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function updateDescription(string $lpId, string $description)
    {
        try {
            $sql = "UPDATE lp SET description = :description, date_edited = NOW() WHERE id = :lpId";
            $this->db->runSQL($sql, [':description' => $description, ':lpId' => $lpId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    // deleting lp
    public function deleteLp($id)
    {
        try {
            $sql = "DELETE FROM lp WHERE id = :id";
            $test = $this->db->runSQL($sql, ['id' => $id]);
            return $test;
        } catch (\PDOException $e) {
            return var_dump($e);
        }
    }
    // creating copy of an lp
    public function createLpCopy(
        $lpId,
        string $title,
        string $userId,
        $language_id,
        $selectedCategory,
        $copied_member_id,
        // values below can be null
        ?string $description = null,
        ?string $thumbnail = null
    ) {
        $sql = "INSERT INTO lp 
                (user_id, copied_member_id, isCopy, thumbnail, title, description, date_created, date_edited, language_id, category_id) 
                VALUES (:user_id, :copied_member_id, TRUE, :thumbnail, :title, :description, NOW(), null, :language_id, :category_id)";
    
        $params = [
            ':user_id' => $userId,
            ':title' => $title,
            ':language_id' => $language_id,
            ':category_id' => $selectedCategory,
            ':copied_member_id' => $copied_member_id,
            ':description' => $description,
            ':thumbnail' => $thumbnail,
        ];
    
        $this->db->runSQL($sql, $params);

        $newLpId = $this->db->lastInsertId();
    
        // Copy sections associated with the original lp to the new lp
        $this->copySections($lpId, $newLpId);
    
        return $newLpId;
    }
    // function that copies sections based on original lp id (and setting them to the new lp id)
    private function copySections($originalLpId, $newLpId) {
        $sql = "INSERT INTO section (lp_id, thumbnail, url, title, description) 
                SELECT :newLpId, thumbnail, url, title, description
                FROM section
                WHERE lp_id = :originalLpId";
    
        $params = [
            ':newLpId' => $newLpId,
            ':originalLpId' => $originalLpId,
        ];
    
        $this->db->runSQL($sql, $params);
    }
}
 