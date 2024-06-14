<?php

namespace src\models;

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../database/Database.php');


use database\Database;
use Exception;
use PDO;
use PDOException;

class Task
{
    /**
     * @var PDO
     */
    private PDO $conn;

    /**
     * Constructor. Establishes database connection.
     */
    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    /**
     * Adds a new task to the database.
     *
     * @param $title
     * @param $description
     * @param null|string $execution_date
     * @return bool
     * @throws Exception
     */
    public function addTask($title, $description, ?string $execution_date = null): bool
    {
        try {
            if (is_null($execution_date)) {
                $execution_date = date('Y-m-d', strtotime('+1 day'));
            }
            $stmt = $this->conn->prepare("INSERT INTO tasks (title, description, execution_date) VALUES (:title, :description, :execution_date)");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':execution_date', $execution_date);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to create task: " . $e->getMessage());
        }
    }

    /**
     *  Retrieves all tasks from the database.
     *
     * @return array|false
     * @throws Exception
     */
    public function getTasks(): array|false
    {
        try {
            $stmt = $this->conn->query("SELECT * FROM tasks");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Failed to retrieve tasks: " . $e->getMessage());
        }
    }

    /**
     * Retrieves a task by its ID from the database.
     *
     * @param int $id
     * @return array|null
     * @throws Exception
     */
    public function getTaskById(int $id): ?array
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $task = $stmt->fetch(PDO::FETCH_ASSOC);
            return $task ?? null;
        } catch (PDOException $e) {
            throw new Exception("Failed to retrieve task with ID $id: " . $e->getMessage());
        }
    }

    /**
     * Updates an existing task in the database.
     *
     * @param $id
     * @param $title
     * @param $description
     * @param null|string $execution_date
     * @return bool
     * @throws Exception
     */
    public function updateTask($id, $title, $description, ?string $execution_date = null): bool
    {
        try {
            if (is_null($execution_date)) {
                $stmt = $this->conn->prepare("SELECT execution_date FROM tasks WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$result) {
                    throw new Exception("Task not found with ID: " . $id);
                }
                $execution_date = $result['execution_date'];
            }

            $stmt = $this->conn->prepare("UPDATE tasks SET title = :title, description = :description, execution_date = :execution_date WHERE id = :id");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':execution_date', $execution_date);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to update task: " . $e->getMessage());
        }
    }


    /**
     * Deletes a task from the database.
     *
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteTask($id): bool
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM tasks WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            throw new Exception("Failed to delete task: " . $e->getMessage());
        }
    }

    /**
     * Check if a task with the given title already exists in the database.
     *
     * @param string $title
     * @return bool
     * @throws Exception
     */
    public function taskExistsWithTitle(string $title): bool
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM tasks WHERE title = :title");
            $stmt->bindParam(':title', $title);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (PDOException $e) {
            throw new Exception("Failed to check if task exists with title: " . $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function taskExistsWithId(int $id): bool
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM tasks WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (PDOException $e) {
            throw new Exception("Failed to check if task exists with ID: " . $e->getMessage());
        }
    }

}