<?php

namespace controllers;

require_once(__DIR__ . '/../models/Task.php');
require_once(__DIR__ . '/../services/ResponseService.php');
require_once(__DIR__ . '/../requests/validators/TaskRequestValidator.php'); // Adjust path as needed

use Exception;
use models\Task;
use PDOException;
use services\ResponseService;
use controllers\requests\validators\TaskRequestValidator; // Adjust namespace as needed

class TaskController
{
    private Task $taskModel;
    private ResponseService $responseService;
    private TaskRequestValidator $validator;

    public function __construct()
    {
        $this->taskModel = new Task();
        $this->responseService = new ResponseService();
        $this->validator = new TaskRequestValidator(); // Instantiate the validator
    }

    public function getAllTasks(): string
    {
        try {
            $tasks = $this->taskModel->getTasks();
        } catch (Exception $e) {
            return $this->responseService->errorResponse($e->getCode(), 'Failed to retrieve tasks', $e->getMessage());
        }
        return $this->responseService->successResponse(200, 'Successfully retrieved items', $tasks);
    }

    public function addTask(): false|string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->responseService->errorResponse(405, 'Method Not Allowed');
        }

        // Get data from the POST request body
        $postData = file_get_contents('php://input');
        parse_str($postData, $data);

        // Validate the data using the validator
        $validationErrors = $this->validator->validateAddTask($data);
        if ($validationErrors !== false) {
            return $this->responseService->errorResponse(400, 'Validation Error', $validationErrors);
        }

        $title = $data['title'];
        $description = $data['description'];
        $executionDate = $data['execution_date'] ?? null;

        try {
            $this->taskModel->addTask($title, $description, $executionDate);
            return $this->responseService->successResponse(200, 'Successfully created task');
        } catch (Exception $e) {
            return $this->responseService->errorResponse($e->getCode(), 'Internal Server Error', 'Failed to create task: ' . $e->getMessage());
        }
    }

    public function updateTask(): string|null
    {
        $postData = file_get_contents('php://input');
        parse_str($postData, $requestData);

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

    public function deleteTask(): string
    {
        try {
            $postData = file_get_contents('php://input');
            parse_str($postData, $requestData);

            $id = $requestData['id'];

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
