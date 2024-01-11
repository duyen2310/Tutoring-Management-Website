<?php
// defning the constant APP_ROOT as the parent directory of the current file (two levels up)
require dirname(__DIR__).'/src/classes/CMS.php';
require dirname(__DIR__).'/src/classes/Validate.php';
require dirname(__DIR__).'/src/classes/CMS/Comment.php';
require dirname(__DIR__).'/src/classes/CMS/Database.php';
require dirname(__DIR__).'/src/classes/CMS/Dislike.php';
require dirname(__DIR__).'/src/classes/CMS/Like.php';
require dirname(__DIR__).'/src/classes/CMS/Lp.php';
require dirname(__DIR__).'/src/classes/CMS/Section.php';
require dirname(__DIR__).'/src/classes/CMS/Session.php';
require dirname(__DIR__).'/src/classes/CMS/User.php';



// db connection details
//$dsn = "mysql:host=localhost;port=3307;dbname=f3410182_project;charset=utf8mb4";
$dsn = "mysql:host=localhost;port=3307;dbname=f3446598_project1;charset=utf8mb4";

$username = "f3446598_mia";
$password = "123Mia123";

// creating a cms instance and passing the database connection details
$cms = new CMS($dsn, $username, $password);

// unset the database connection details to clear sensitive information
unset($dsn, $username, $password);
?>