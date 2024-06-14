<?php

namespace controllers;

require_once(__DIR__ . '/../models/Task.php');
require_once(__DIR__ . '/../services/ResponseService.php');
require_once(__DIR__ . '/../requests/validators/TaskRequestValidator.php');

use Exception;
use src\models\Task;
use PDOException;
use services\ResponseService;
use src\requests\validators\TaskRequestValidator;

class TaskController
{
    /**
     * @var Task $taskModel Instance of Task model.
     */
    private Task $taskModel;

    /**
     * @var ResponseService $responseService Instance of ResponseService.
     */
    private ResponseService $responseService;

    /**
     * @var TaskRequestValidator $validator Instance of TaskRequestValidator.
     */
    private TaskRequestValidator $validator;

    /**
     * Initializes the task model, response service, and validator.
     */
    public function __construct()
    {
        $this->taskModel = new Task();
        $this->responseService = new ResponseService();
        $this->validator = new TaskRequestValidator();
    }

    /**
     * Retrieves all tasks from the database.
     * @return string
     */
    public function getAllTasks(): string
    {
        try {
            $tasks = $this->taskModel->getTasks();
        } catch (Exception $e) {
            return $this->responseService->errorResponse($e->getCode(), 'Failed to retrieve tasks', $e->getMessage());
        }
        return $this->responseService->successResponse(200, 'Successfully retrieved items', $tasks);
    }

    /**
     * Retrieves a task by its ID from the database and returns it as JSON.
     *
     * @return string
     */
    public function showTask(): string
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                return $this->responseService->errorResponse(405, 'Method Not Allowed');
            }

            $data = [
                'id' => $_GET['id']
            ];

            $validationErrors = $this->validator->validateShowTask($data, $this->taskModel);
            if ($validationErrors !== false) {
                return $this->responseService->errorResponse(400, 'Validation Error', $validationErrors);
            }

            $id = $data['id'] ?? null;
            $task = $this->taskModel->getTaskById($id);

            if ($task) {
                return $this->responseService->successResponse(200, 'Task found', $task);
            } else {
                return $this->responseService->errorResponse(404, 'Task not found');
            }
        } catch (Exception $e) {
            return $this->responseService->errorResponse($e->getCode(), 'Failed to retrieve task', $e->getMessage());
        }
    }

    /**
     * Adds a new task to the database.
     * @return false|string
     */
    public function addTask(): false|string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->responseService->errorResponse(405, 'Method Not Allowed');
        }
        $postData = file_get_contents('php://input');
        $requestData = json_decode($postData, true);

        // Check if decoding was successful
        if ($requestData === null) {
            $requestData = [];
            parse_str($postData, $requestData);
        }

        if (!is_array($requestData)) {
            $requestData = [];
        }

        $validationErrors = $this->validator->validateAddTask($requestData, $this->taskModel);
        if ($validationErrors !== false) {
            return $this->responseService->errorResponse(400, 'Validation Error', $validationErrors);
        }

        $title = $requestData['title'];
        $description = $requestData['description'];
        $executionDate = $requestData['execution_date'] ?? null;

        try {
            $this->taskModel->addTask($title, $description, $executionDate);
            return $this->responseService->successResponse(200, 'Successfully created task');
        } catch (Exception $e) {
            return $this->responseService->errorResponse($e->getCode(), 'Internal Server Error', 'Failed to create task: ' . $e->getMessage());
        }
    }

    /**
     * Updates an existing task in the database.
     * @return string|null
     */
    public function updateTask(): string|null
    {
        $postData = file_get_contents('php://input');
        $requestData = json_decode($postData, true);

        // Check if decoding was successful
        if ($requestData === null) {
            $requestData = [];
            parse_str($postData, $requestData);
        }

        if (!is_array($requestData)) {
            $requestData = [];
        }

        // Validate the data using the validator
        $validationErrors = $this->validator->validateUpdateTask($requestData);
        if ($validationErrors !== false) {
            return $this->responseService->errorResponse(400, 'Validation Error', $validationErrors);
        }

        $id = $requestData['id'];
        $title = $requestData['title'] ?? '';
        $description = $requestData['description'] ?? '';
        $executionDate = $requestData['execution_date'] ?? null;

        if (!empty($title) && !empty($description) && !empty($id)) {
            try {
                $this->taskModel->updateTask($id, $title, $description, $executionDate);
                return $this->responseService->successResponse(200, 'Task updated successfully');
            } catch (Exception $e) {
                return $this->responseService->errorResponse($e->getCode(), 'Failed to update task', $e->getMessage());
            }
        } else {
            return $this->responseService->errorResponse(400, 'Id, Title and Description are required');
        }
    }

    /**
     * Deletes a task from the database.
     * @return string
     */
    public function deleteTask(): string
    {
        try {
            $postData = file_get_contents('php://input');
            $requestData = json_decode($postData, true);

            // Check if decoding was successful
            if ($requestData === null) {
                $requestData = [];
                parse_str($postData, $requestData);
            }

            if (!is_array($requestData)) {
                $requestData = [];
            }

            $id = $requestData['id'] ?? null;

            $validationErrors = $this->validator->validateDeleteTask($requestData);
            if ($validationErrors !== false) {
                return $this->responseService->errorResponse(400, 'Validation Error', $validationErrors);
            }

            try {
                $this->taskModel->deleteTask($id);
                return $this->responseService->successResponse(200, 'Task deleted successfully');
            } catch (Exception $e) {
                return $this->responseService->errorResponse($e->getCode(), 'Failed to delete task', $e->getMessage());
            }
        } catch (PDOException $e) {
            return $this->responseService->errorResponse($e->getCode(), 'Failed to delete task', $e->getMessage());
        }
    }

}

