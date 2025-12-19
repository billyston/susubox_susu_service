<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Exceptions;

use Exception;

final class InsufficientBalanceException extends Exception
{
    /**
     * @param $message
     */
    public function __construct(
        $message = 'Insufficient balance.'
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
