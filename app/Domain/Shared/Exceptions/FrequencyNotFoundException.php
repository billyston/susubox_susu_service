<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exceptions;

use Exception;

final class FrequencyNotFoundException extends Exception
{
    /**
     * @param $message
     */
    public function __construct(
        $message = 'Savings frequency not found'
    ) {
        parent::__construct($message, 404);
    }

    /**
     * @return void
     */
    public function report(
    ) {
        //..
    }
}
