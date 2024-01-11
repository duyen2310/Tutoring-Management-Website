<?php
// A FEW SQL QUERIES HERE AREN'T USED HOWEVER SINCE WE PLAN ON IMPROVING THIS PROJECT ON OUR FREE TIME WE WOULD LIKE TO HAVE THEM
// AVAILIBLE FOR WHEN WE DO WANT TO USE THEM
// CMS class acts as a central point for managing various components related to Content Management System 
// (CMS method used in this projects were learned by JON DUCKETT's php and mysql)
class CMS {
    protected $db = null;       // Database connection object
    
    // Components related to specific functionalities in the CMS
    protected $lp = null;      
    protected $section = null;  
    protected $user = null;      
    protected $comment = null;  
    protected $session = null;  
    protected $like = null;     
    protected $dislike = null;  
    
    // Constructor to initialize the CMS class with a database connection
    public function __construct($dsn, $username, $password) {
        $this->db = new CMS\Database($dsn, $username, $password);
    }
    
    
    // Method to get the Learning Path component
    public function getLp() {
        if ($this->lp == null) {
            $this->lp = new CMS\Lp($this->db);
        }
        return $this->lp;
    }

    // Method to get the Section component
    public function getSection() {
        if ($this->section == null) {
            $this->section = new CMS\Section($this->db);
        }
        return $this->section;
    }

    // Method to get the Comment component
    public function getComment() {
        if ($this->comment == null) {
            $this->comment = new CMS\Comment($this->db);
        }
        return $this->comment;
    }

    // Method to get the User component
    public function getUser() {
        if ($this->user == null) {
            $this->user = new CMS\User($this->db);
        }
        return $this->user;
    }

    // Method to get the Session component
    public function getSession() {
        if ($this->session == null) {
            $this->session = new CMS\Session($this->db);
        }
        return $this->session;
    }

    // Method to get the Like component
    public function getLike() {
        if ($this->like == null) {
            $this->like = new CMS\Like($this->db);
        }
        return $this->like;
    }

    // Method to get the Dislike component
    public function getDislike() {
        if ($this->dislike == null) {
            $this->dislike = new CMS\Dislike($this->db);
        }
        return $this->dislike;
    }
}


?>
