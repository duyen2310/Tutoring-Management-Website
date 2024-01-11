<?php

namespace CMS;

use Exception;

class Section
{
    protected $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    // get one seciton that matches the id supplied
    public function get($id)
    {
        try{
        $sql = "
            SELECT
                s.id,
                s.thumbnail,
                s.title,
                s.lp_id,
                u.username AS username,
                u.id AS user_id
            FROM
                section s
            JOIN lp ON s.lp_id = lp.id
            LEFT JOIN user u ON
                CASE
                    WHEN lp.isCopy = 1 THEN u.id = lp.copied_member_id
                    ELSE u.id = lp.user_id
                END
            WHERE
                s.id = :id;
        ";

        return $this->db->runSQL($sql, [':id' => $id])->fetch();}
        catch(Exception $e){
            return false;
        }
    }
    // get all sections that match the id required
    public function getAllId(string $id)
    {
        $sql = "
        SELECT 
    s.id, 
    s.thumbnail, 
    s.url,  
    s.title,
    s.description,
    s.date_created,
    s.date_edited,
    s.views,
    lp.user_id,
    c.name AS category_name,
    (SELECT username FROM user u WHERE u.id = user_id) AS username,
    user_id
FROM
    section s
JOIN lp ON lp.id = s.lp_id
JOIN category c ON lp.category_id = c.id
WHERE
    lp.id = :id;
        ";
        return $this->db->runSQL($sql, [':id' => $id])->fetchAll();
    }
    public function getAll(int $id): array
    {
        $sql = "SELECT s.thumbnail,s.url,s.title,s.description,s.date_created,s.date_edited,s.views,
            CASE
            WHEN isCopy = 1 THEN (SELECT username FROM users u WHERE u.id = copied_member_id)
            ELSE (SELECT username FROM user u WHERE u.id = member_id)
            END AS username
            WHERE lp.id=:id
            ";
        return $this->db->runSQL($sql, [$id])->fetchAll();
    }

    // creating a section (using begintransaction here incase we fail a part of the sql so we can reroll after)
    public function createSection(string $title, string $lpId, string $url)
    {
        try {
            // Insert into the 'section' table
            $sql = "
                INSERT INTO section (lp_id, thumbnail, url, title, description, date_created, date_edited, views) 
                VALUES (:lp_id, null, :url, :title, null, NOW(), null, 0);
            ";
    
            $this->db->runSQL($sql, [':title' => $title, ':url' => $url, ':lp_id' => $lpId]);
    
            $secId = $this->db->lastInsertId();
    
            return $secId;
        } catch (Exception $e) {
            // Handle the exception as needed
            throw $e;
        }
    }

    // adding description and others below how we did in user
    public function addDescription(int $secId, string $description)
    {
        try {
            $sql = "UPDATE section SET description = :description WHERE id = :secId";
            $this->db->runSQL($sql, [':description' => $description, ':secId' => $secId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function addThumbnail(int $secId, string $thumbnail)
    {
        try {
            $sql = "UPDATE section SET thumbnail = :thumbnail WHERE id = :secId";
            $this->db->runSQL($sql, [':thumbnail' => $thumbnail, ':secId' => $secId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function updateTitle(string $secId, string $title)
    {
        try {
            $sql = "UPDATE section SET title = :title, date_edited = NOW() WHERE id = :secId";
            $this->db->runSQL($sql, [':title' => $title, ':secId' => $secId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function updateUrl(string $secId, string $url)
    {
        try {
            $sql = "UPDATE section SET date_edited = NOW(), url = :url WHERE id = :secId";
            $this->db->runSQL($sql, [':url' => $url, ':secId' => $secId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function editThumbnail(string $secId, string $thumbnail)
    {
        try {
            $sql = "UPDATE section SET thumbnail = :thumbnail, date_edited = NOW() WHERE id = :secId";
            $this->db->runSQL($sql, [':thumbnail' => $thumbnail, ':secId' => $secId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function editDescription(string $secId, string $description)
    {
        try {
            $sql = "UPDATE section SET description = :description,date_edited = NOW() WHERE id = :secId";
            $this->db->runSQL($sql, [':description' => $description, ':secId' => $secId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function deleteSection($id)
    {
        try {
            $sql = "DELETE FROM section WHERE id = :id";
            $test = $this->db->runSQL($sql, ['id' => $id]);
            return $test;
        } catch (\PDOException $e) {
            return false;
        }
    }
    // incrementing the views in the database
    public function incrementViews($sectionId)
    {
        try {
            $sql = "UPDATE section SET views = views + 1 WHERE id = :sectionId";
            $this->db->runSQL($sql, [':sectionId' => $sectionId]);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
