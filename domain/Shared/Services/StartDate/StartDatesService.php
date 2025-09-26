<?php

declare(strict_types=1);

namespace Domain\Shared\Services\StartDate;

use App\Exceptions\Common\SystemFailureException;
use Domain\Shared\Models\StartDate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class StartDatesService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
    ): Collection {
        try {
            return StartDate::get();
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw $modelNotFoundException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in StartDatesService', [
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException;
        }
    }
}
