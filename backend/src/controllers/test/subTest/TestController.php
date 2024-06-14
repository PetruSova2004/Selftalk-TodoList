<?php

namespace controllers\test\subTest;

require_once(__DIR__ . '/../../../models/Task.php');

use src\models\Task;

class TestController
{
    /**
     * The task model instance.
     * @var Task
     */
    private Task $taskModel;

    /**
     * Initializes the task model.
     */
    public function __construct() {
        $this->taskModel = new Task();
    }

    /**
     *  Retrieves all tasks from the database.
     *
     */
    public function test(): string
    {
        try {
            echo 89;
            $tasks = $this->taskModel->getTasks();
            return json_encode(['success' => true, 'data' => $tasks]);
        } catch(PDOException $e) {
            return json_encode([
                'success' => false,
                'error' => 'Failed to retrieve tasks',
                'message' => $e->getMessage()
            ]);
        }
    }


}
