<?php

namespace CMS;

class Like
{
    protected $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    // getting only likes
    public function get(array $like)
    {
        $sql = "SELECT COUNT(*)
            FROM likes WHERE lp_id = :id AND user_id = :user_id";
        return $this->db->runSQL($sql, $like)->fetchColumn();;
    }
    // creating like
    public function create(array $like): bool
    {
        $sql = "INSERT INTO likes (lp_id,user_id,date_liked)
                    VALUES (:lp_id,:user_id,NOW())";
        $this->db->runSQL($sql, $like);
        return true;
    }
    // deleting like
    public function delete(array $like): bool
    {
        $sql = "DELETE FROM likes WHERE lp_id = :lp_id AND user_id = :user_id";
        $this->db->runSQL($sql, $like);
        return true;
    }
    // selecting all the likes for one article
    public function selectAllForArticle(int $lp_id)
    {
        $sql = "SELECT COUNT(*)
            FROM likes WHERE lp_id = :id";
        return $this->db->runSQL($sql, [$lp_id])->fetchColumn();
    }
    //
    // finding the ammount of likes and dislikes and returning the difference
    public function likesToDislikes(int $lp_id)
    {
        $sql = $sql = "SELECT
        (SELECT COUNT(*) FROM likes WHERE lp_id = :id) AS like_count,
        (SELECT COUNT(*) FROM dislikes WHERE lp_id = :id1) AS dislike_count,
        (SELECT COUNT(*) FROM likes WHERE lp_id = :id2) - (SELECT COUNT(*) FROM dislikes WHERE lp_id = :id3) AS like_diff
    FROM dual";

        $parameters = [
            'id' => $lp_id,
            'id1' => $lp_id,
            'id2' => $lp_id,
            'id3' => $lp_id
        ];

        return $this->db->runSQL($sql, $parameters)->fetch();
    }
}
