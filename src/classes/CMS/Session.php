<?php
// instead of ussing $_Session variable JON DUCKETT'S php and mysql suggests using the following in a CMS
// which also makes code a bit shorter and easier to write
namespace CMS;
    class Session{
        public $id;
        public $username;
        
        public function __construct(){
            session_start();
            $this->id = $_SESSION['id'] ?? 0;
            $this->username = $_SESSION['username'] ?? '';
        }
        // create /update session 
        public function create ($user){
            session_regenerate_id(true);
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        }
        public function update($user){
            $this->create($user);
        }
        // delete session
        public function delete(){
            $_SESSION = [];
        
            $param = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600, $param['path'], $param['domain'], $param['secure'], $param['httponly']);
        
            session_destroy(); 
        }
        
    }

?>