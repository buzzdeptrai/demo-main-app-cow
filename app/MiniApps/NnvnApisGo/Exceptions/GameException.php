<?php

namespace App\MiniApps\NnvnApisGo\Exceptions;

use Exception;

class GameException extends Exception
{
    private string $errorCode;

    public function __construct(string $message, string $errorCode, int $httpCode = 400)
    {
        parent::__construct($message, $httpCode);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getHttpCode(): int
    {
        return $this->getCode();
    }

    public static function maxApisReached(): self
    {
        return new self(
            'Player has already found the maximum number of APIs.',
            'MAX_APIS_REACHED',
            403
        );
    }

    public static function gameNotActive(): self
    {
        return new self(
            'Game is not in active/playing state.',
            'GAME_NOT_ACTIVE',
            400
        );
    }

    public static function invalidRoundTime(): self
    {
        return new self(
            'Round time must be between 2 and 30 seconds.',
            'INVALID_ROUND_TIME',
            400
        );
    }

    public static function roundExists(): self
    {
        return new self(
            'This round has already been submitted.',
            'ROUND_EXISTS',
            409
        );
    }
}
