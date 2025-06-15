<?php

namespace App\Exceptions;

class ResourceNotFoundException extends ApiException
{
    /**
     * ResourceNotFoundException constructor.
     *
     * @param string $resourceType
     * @param mixed $identifier
     * @param array $headers
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $resourceType = 'Resource',
        mixed $identifier = null,
        array $headers = [],
        ?\Throwable $previous = null
    ) {
        $message = $resourceType;

        if ($identifier !== null) {
            $message .= " with identifier '{$identifier}'";
        }

        $message .= ' not found';

        parent::__construct($message, 404, [], $headers, $previous);
    }
}
