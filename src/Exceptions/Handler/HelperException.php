<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Exceptions\Handler;

use PROLANCEE\DYNAMIC\CRUD\Ajax\Exceptions\PackageException;
use Illuminate\Http\JsonResponse;
use Exception;
use Throwable;

class HelperException extends Exception
{
    protected array $payload = [];

    /**
     * Constructor.
     *
     * @param array $payload Custom exception data (message, code, extra)
     * @param int $code HTTP status code (default 0)
     * @param Throwable|null $previous Previous exception for chaining
     */
    public function __construct(array $payload = [], int $code = 0, ?Throwable $previous = null)
    {
        $this->payload = $payload;
        $message = $payload['message'] ?? 'HelperException triggered';

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get title from payload.
     */
    public function getTitle(): ?string
    {
        return $this->payload['title'] ?? null;
    }

    /**
     * Get message from payload.
     */
    public function getCustomMessage(): ?string
    {
        return $this->payload['message'] ?? $this->getMessage();
    }

    /**
     * Get code from payload.
     */
    public function getCustomCode(): int
    {
        return $this->payload['code'] ?? $this->getCode();
    }

    /**
     * Get extra options from payload.
     */
    public function getOptions(): array
    {
        return $this->payload['option'] ?? [];
    }

    /**
     * Convert exception to array via PackageException renderer.
     *
     * @param Throwable $exception
     * @return array
     */
    public function toArray(?Throwable $exception = null): array
    {
        $base = array_filter([
            'title'   => $this->getTitle(),
            'message' => $this->getCustomMessage(),
            'code'    => $this->getCustomCode(),
        ], fn($v) => !is_null($v));

        $payload = array_merge($base, $this->getOptions());

        return (new PackageException(request()))->render($payload, $exception);
    }

    /**
     * Convert exception to JSON response.
     *
     * @param Throwable $exception
     * @return JsonResponse
     */
    public function toJsonResponse(?Throwable $exception = null): JsonResponse
    {
        return response()->json(
            $this->toArray($exception),
            $this->getCustomCode() ?: 500
        );
    }
}
