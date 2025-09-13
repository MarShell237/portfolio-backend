<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function ok(string $message, array $data = [], bool $success = true): JsonResponse
    {
        return self::build($success, Response::HTTP_OK, $message, $data);
    }

    public static function created(string $message, array $data = [], bool $success = true): JsonResponse
    {
        return self::build($success, Response::HTTP_CREATED, $message, $data);
    }

    public static function badRequest(string $message, array $data = [], bool $success = false): JsonResponse
    {
        return self::build($success, Response::HTTP_BAD_REQUEST, $message, $data);
    }

    public static function unauthorized(string $message, array $data = [], bool $success = false): JsonResponse
    {
        return self::build($success, Response::HTTP_UNAUTHORIZED, $message, $data);
    }

    public static function forbidden(string $message, array $data = [], bool $success = false): JsonResponse
    {
        return self::build($success, Response::HTTP_FORBIDDEN, $message, $data);
    }

    public static function anyError(string $message, int $status, array $data = [], bool $success = false): JsonResponse
    {
            if ($status < 100 || $status > 599) {
                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        return self::build($success, $status, $message, $data);
    }

    private static function build(bool $success, int $status, string $message, array $data = []): JsonResponse
    {
        $response = [
            'success' => $success,
            'status' => $status,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response = array_merge($response, $data);
        }

        return response()->json($response, $status);
    }
}
