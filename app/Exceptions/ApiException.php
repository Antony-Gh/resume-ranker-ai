<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    /**
     * @var int
     */
    protected int $statusCode = 500;

    /**
     * @var array
     */
    protected array $headers = [];

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * ApiException constructor.
     *
     * @param string $message
     * @param int|null $statusCode
     * @param array $data
     * @param array $headers
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Server Error',
        ?int $statusCode = null,
        array $data = [],
        array $headers = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);

        if ($statusCode !== null) {
            $this->statusCode = $statusCode;
        }

        $this->data = $data;
        $this->headers = $headers;
    }

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get the additional data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(): JsonResponse
    {
        $response = [
            'message' => $this->getMessage(),
        ];

        if (!empty($this->data)) {
            $response['data'] = $this->data;
        }

        if (config('app.debug')) {
            $response['debug'] = [
                'file' => $this->getFile(),
                'line' => $this->getLine(),
            ];
        }

        return response()->json($response, $this->statusCode, $this->headers);
    }
}
