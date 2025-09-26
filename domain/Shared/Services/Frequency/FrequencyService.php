<?php

declare(strict_types=1);

namespace Domain\Shared\Services\Frequency;

use App\Exceptions\Common\SystemFailureException;
use Domain\Shared\Exceptions\FrequencyNotFoundException;
use Domain\Shared\Models\Frequency;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class FrequencyService
{
    /**
     * @throws SystemFailureException
     * @throws FrequencyNotFoundException
     */
    public function execute(
        string $frequency_code
    ): Frequency {
        try {
            // Execute the DB transaction and return the Transaction
            return Frequency::where([
                ['code', '=', $frequency_code],
                ['is_allowed', '=', true],
            ])->firstOrFail();
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw new FrequencyNotFoundException(
                message: 'The savings frequency is either not found or not allowed.'
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in FrequencyService', [
                'frequency_code' => $frequency_code,
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
