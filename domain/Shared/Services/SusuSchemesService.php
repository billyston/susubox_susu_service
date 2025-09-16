<?php

declare(strict_types=1);

namespace Domain\Shared\Services;

use App\Exceptions\Common\SystemFailureExec;
use Domain\Shared\Enums\SusuSchemeStatus;
use Domain\Shared\Models\SusuScheme;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SusuSchemesService
{
    /**
     * @throws SystemFailureExec
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

            // Throw the SystemFailureExec
            throw new SystemFailureExec;
        }
    }
}
