<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Throwable;

class ApiException extends Exception implements Responsable
{
    public readonly string $customCode;

    public readonly int $status;

    public readonly bool $silenced;

    public function __construct(
        string $message,
        string $code,
        ?int $status = null,
        bool $silenced = false,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);

        $this->silenced = $silenced;
        $this->customCode = $code;
        $this->status = $status ?? 400;
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'code' => $this->customCode,
            'message' => $this->message,
        ], $this->status);
    }
}
