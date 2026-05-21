<?php

namespace KanxPHP\Core\Exceptions;

use Exception;
use Throwable;

class IpaasException extends Exception
{
    private string $ipaasErrorCode;
    private int $httpStatus;
    private array $contextMetadata;

    public function __construct(
        string $ipaasErrorCode,
        string $message,
        int $httpStatus = 400,
        array $contextMetadata = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $httpStatus, $previous);
        $this->ipaasErrorCode = $ipaasErrorCode;
        $this->httpStatus = $httpStatus;
        $this->contextMetadata = $contextMetadata;
    }

    public function getIpaasErrorCode(): string
    {
        return $this->ipaasErrorCode;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    /**
     * Compiles an immutable error dictionary data block 
     * ready for immediate JSON proxy serialization.
     */
    public function toResponseArray(): array
    {
        return [
            'success' => false,
            'gateway_error' => [
                'code' => $this->ipaasErrorCode,
                'message' => $this->getMessage(),
                'timestamp' => date('Y-m-d\TH:i:s\Z'),
                'context_metadata' => $this->contextMetadata
            ]
        ];
    }
}
