<?php
require_once(__DIR__ . '/../../database/Database.php');

use database\Database;

try {
    // Get the PDO connection
    $pdo = Database::getConnection();

    // SQL to create tasks table
    $sql = "
        CREATE TABLE IF NOT EXISTS tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            execution_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
    ";

    // Execute the SQL query
    $pdo->exec($sql);

    echo "Table 'tasks' created successfully. \n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}