<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exceptions;

use Exception;

final class SusuSchemeNotFoundException extends Exception
{
    public function __construct(
        $message = 'Susu scheme not found'
    ) {
        parent::__construct($message, 404);
    }

    public function report(
    ) {
        //..
    }
}
