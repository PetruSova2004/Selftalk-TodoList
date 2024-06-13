<?php

namespace database;

require_once(__DIR__ . '/../config/config.php');

use PDO;
use PDOException;

class Database
{
    /**
     * @var PDO|null
     */
    private static ?PDO $conn = null;

    /**
     * Get the PDO instance for database connection.
     *
     * @return PDO
     * @throws PDOException
     */
    public static function getConnection(): PDO
    {
        if (self::$conn === null) {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
            self::$conn = new PDO($dsn, DB_USER, DB_PASSWORD);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$conn;
    }
}