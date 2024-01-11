<?php

namespace CMS;

// Extend PDO to create a custom Database class
class Database extends \PDO {
    protected $pdo = null;

    // Constructor to initialize the Database class with connection details and options
    public function __construct(string $dsn, string $username, string $password, array $options = []) {
        // Set default options for the PDO instance
        $default_options[\PDO::ATTR_DEFAULT_FETCH_MODE] = \PDO::FETCH_ASSOC;
        $default_options[\PDO::ATTR_EMULATE_PREPARES] = false;
        $default_options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;

        // Merge default options with custom options
        $options = array_replace($default_options, $options);

        // Call the parent constructor to create the PDO instance
        parent::__construct($dsn, $username, $password, $options);
    }

    // Execute an SQL query
    public function runSQL(string $sql, $arguments = null) {
        // If no arguments are provided, execute a simple query
        if (!$arguments) {
            return $this->query($sql);
        }

        // Prepare and execute a parameterized query with arguments
        $statement = $this->prepare($sql);
        $statement->execute($arguments);

        return $statement;
    }
}

?>
