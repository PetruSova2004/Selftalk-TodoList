<?php

namespace src\requests\validators;

class TaskRequestValidator
{
    /**
     * Validate the data for adding a new task.
     *
     * @param array $data The data received from the request.
     * @param $taskModel
     * @return array|false Array of validation errors or false if validation passes.
     */
    public function validateAddTask(array $data, $taskModel): array|false
    {
        $errors = [];

        // Validate required fields
        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        } elseif (!$this->isValidString($data['title'])) {
            $errors['title'] = 'Invalid characters in title';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Description is required';
        } elseif (!$this->isValidString($data['description'])) {
            $errors['description'] = 'Invalid characters in description';
        }

        if ($taskModel->taskExistsWithTitle($data['title'])) {
            $errors['title'] = 'A task with this title already exists';
        }

        return empty($errors) ? false : $errors;
    }

    /**
     * Validate the data for retrieving a task by its ID.
     *
     * @param array $data
     * @param $taskModel
     * @return array|false
     */
    public function validateShowTask(array $data, $taskModel): array|false
    {
        $errors = [];

        if (empty($data['id'])) {
            $errors['id'] = 'Task ID is required';
        } elseif (!ctype_digit((string) $data['id'])) {
            $errors['id'] = 'Task ID must be a valid integer';
        } else {
            // Check if task with given ID exists
            $id = (int) $data['id'];
            if (!$taskModel->taskExistsWithId($id)) {
                $errors['id'] = 'Task with ID ' . $id . ' does not exist';
            }
        }

        return empty($errors) ? false : $errors;
    }

    /**
     * Validate the data for updating an existing task.
     *
     * @param array $data The data received from the request.
     * @return array|false Array of validation errors or false if validation passes.
     */
    public function validateUpdateTask(array $data): array|false
    {
        $errors = [];

        // Validate required fields
        if (empty($data['id'])) {
            $errors['id'] = 'Task ID is required';
        } elseif (!ctype_digit((string) $data['id'])) {
            $errors['id'] = 'Task ID must be a valid integer';
        }

        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        } elseif (!$this->isValidString($data['title'])) {
            $errors['title'] = 'Invalid characters in title';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Description is required';
        } elseif (!$this->isValidString($data['description'])) {
            $errors['description'] = 'Invalid characters in description';
        }

        return empty($errors) ? false : $errors;
    }

    /**
     * Validate the data for deleting a task.
     *
     * @param array $data The data received from the request.
     * @return array|false Array of validation errors or false if validation passes.
     */
    public function validateDeleteTask(array $data): array|false
    {
        $errors = [];

        // Validate required fields
        if (empty($data['id'])) {
            $errors['id'] = 'Task ID is required';
        } elseif (!ctype_digit((string) $data['id'])) {
            $errors['id'] = 'Task ID must be a valid integer';
        }

        return empty($errors) ? false : $errors;
    }

    /**
     * Check if a string contains only safe characters.
     *
     * @param string $input The string to validate.
     * @return bool True if the string contains only safe characters, false otherwise.
     */
    private function isValidString(string $input): bool
    {
        // Define a regex pattern to allow only alphanumeric characters, spaces, and basic punctuation
        return preg_match('/^[a-zA-Z0-9\s.,!?-]+$/', $input) === 1;
    }
}
