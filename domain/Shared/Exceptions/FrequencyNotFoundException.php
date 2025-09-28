<?php

declare(strict_types=1);

namespace Domain\Shared\Exceptions;

use Exception;

final class FrequencyNotFoundException extends Exception
{
    public function __construct(
        $message = 'Savings frequency not found'
    ) {
        parent::__construct($message, 404);
    }

    public function report(
    ) {
        //..
    }
}
