<?php

declare(strict_types=1);

namespace App\Domain\Shared\Services;

use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\SusuScheme;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SusuSchemeService
{
    /**
     * @throws SystemFailureException
     * @throws SusuSchemeNotFoundException
     */
    public function execute(
        string $scheme_code
    ): SusuScheme {
        try {
            // Execute the DB transaction and return the Transaction
            return SusuScheme::query()->where([
                ['code', '=', $scheme_code],
                ['status', '=', 'active'],
            ])->firstOrFail();
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw new SusuSchemeNotFoundException(
                message: 'The susu scheme is inactive.'
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in SusuSchemeService', [
                'scheme_code' => $scheme_code,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while trying to fetch the susu scheme.'
            );
        }
    }
}
