<?php
// same as like  but for dislike
namespace CMS;

class Dislike
{
    protected $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    public function get(array $dislike)
    {
        $sql = "SELECT COUNT(*)
            FROM dislikes WHERE lp_id = :id AND user_id = :user_id";
        return $this->db->runSQL($sql, $dislike)->fetchColumn();;
    }
    public function create(array $dislike): bool
    {
        $sql = "INSERT INTO dislikes (lp_id,user_id,date_disliked)
                    VALUES (:lp_id,:user_id,NOW())";
        $this->db->runSQL($sql, $dislike);
        return true;
    }
    public function delete(array $dislike): bool
    {
        $sql = "DELETE FROM dislikes WHERE lp_id = :lp_id AND user_id = :user_id";
        $this->db->runSQL($sql, $dislike);
        return true;
    }
    public function selectAllForArticle(int $lp_id)
    {
        $sql = "SELECT COUNT(*)
            FROM dislikes WHERE lp_id = :id";
        return $this->db->runSQL($sql, [$lp_id])->fetchColumn();
    }

}
