<?php

declare(strict_types=1);

namespace Domain\Susu\Exceptions\Account;

use Exception;

final class CancellationNotAllowedException extends Exception
{
    public function __construct(
        string $message = 'You are not authorized to perform this action.'
    ) {
        parent::__construct($message, 401);
    }

    public function report(
    ) {
        //..
    }
}
