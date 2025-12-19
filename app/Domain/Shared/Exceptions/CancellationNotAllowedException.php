<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exceptions;

use Exception;

final class CancellationNotAllowedException extends Exception
{
    /**
     * @param string $message
     */
    public function __construct(
        string $message = 'You are not authorized to perform this action.'
    ) {
        parent::__construct($message, 401);
    }

    /**
     * @return void
     */
    public function report(
    ) {
        //..
    }
}
