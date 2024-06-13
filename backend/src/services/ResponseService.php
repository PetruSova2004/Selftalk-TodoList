<?php

namespace services;

class ResponseService
{
    /**
     * @param int $status
     * @param string $message
     * @param array|null $data
     * @return string
     */
    public function successResponse(int $status, string $message, ?array $data = null): string
    {
        return json_encode([
            'success' => true,
            'status' => $status,
            'message' => $message,
            'data' => $data ?? [],
        ]);
    }

    /**
     * @param int $status
     * @param string $message
     * @param null $error
     * @return string
     */
    public function errorResponse(int $status, string $message, $error = null): string
    {
        return json_encode([
            'success' => false,
            'status' => $status,
            'message' => $message,
            'error' => $error
        ]);
    }
}