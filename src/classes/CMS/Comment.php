<?php
namespace CMS;
    class Comment{
        protected $db;
        public function __construct(Database $db){
            $this->db= $db;
        }
        // getting just one comment from the id provided
        public function getOneOnly(int $id){
            $sql ="SELECT u.username, u.image_url, c.text, c.date_posted
            FROM comment c
            JOIN users u ON u.id = c.user_id
            JOIN lp l ON l.id = c.lp_id
            WHERE l.id = :id";
            return $this->db->runSQL($sql,[$id])->fetch();
        }
        // getting all the comments from the id provded
        public function getOneAll(int $id){
            $sql ="SELECT u.username, u.image_url, c.text, c.date_posted
            FROM comment c
            JOIN users u ON u.id = c.user_id
            JOIN lp l ON l.id = c.lp_id
            WHERE l.id = :id";
            return $this->db->runSQL($sql,[$id])->fetchAll();
        }
        // getting all comments
        public function getAll() : array{
            $sql ="SELECT u.username, u.image_url, c.text, c.date_posted
            FROM comment c
            JOIN users u ON u.id = c.user_id
            JOIN lp l ON l.id = c.lp_id";
            return $this->db->runSQL($sql)->fetchAll();
        }
        
        // creating a comment
        public function create(array $comment) : bool{
            $sql = "INSERT INTO comment (text,date_posted,lp_id,user_id)
                    VALUES (:comment,NOW(),:lp_id,:user_id)";
                $this->db->runSQL($sql,$comment);
                return true;
        }
        // getting all the comments for an lp provided
        public function getForLp($lp_id){
            $sql = "SELECT u.username, u.image_url, c.text, c.date_posted,c.user_id,c.lp_id,c.id
            FROM comment c 
            JOIN user u ON u.id = c.user_id
            WHERE c.lp_id = :lp_id"; 
            return $this->db->runSQL($sql,[':lp_id'=>$lp_id])->fetchAll();
        }
        // deleting a comment
        public function delete($id){
            $sql = "DELETE FROM comment WHERE id = :id";
            $this->db->runSQL($sql, [':id' => $id]);
        }
    }

?>