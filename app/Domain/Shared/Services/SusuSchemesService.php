<?php

declare(strict_types=1);

namespace App\Domain\Shared\Services;

use App\Domain\Shared\Enums\SusuSchemeStatus;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\SusuScheme;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SusuSchemesService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
    ): Collection {
        try {
            return SusuScheme::where(
                'status',
                SusuSchemeStatus::ACTIVE->value,
            )->get();
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw $modelNotFoundException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in SusuSchemesService', [
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
