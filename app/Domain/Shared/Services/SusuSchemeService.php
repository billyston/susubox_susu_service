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
     * @param string $schemeCode
     * @return SusuScheme
     * @throws SusuSchemeNotFoundException
     * @throws SystemFailureException
     */
    public function execute(
        string $schemeCode
    ): SusuScheme {
        try {
            // Execute the DB transaction and return the Transaction
            return SusuScheme::query()->where([
                ['code', '=', $schemeCode],
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
                'scheme_code' => $schemeCode,
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
