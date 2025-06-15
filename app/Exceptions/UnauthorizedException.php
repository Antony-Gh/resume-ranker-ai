<?php

namespace App\Exceptions;

class UnauthorizedException extends ApiException
{
    /**
     * UnauthorizedException constructor.
     *
     * @param string $message
     * @param array $headers
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'You are not authorized to perform this action',
        array $headers = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 403, [], $headers, $previous);
    }
}
