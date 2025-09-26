<?php

declare(strict_types=1);

namespace Domain\Shared\Services\Scheme;

use App\Exceptions\Common\SystemFailureException;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Models\SusuScheme;
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
            return SusuScheme::where([
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
            throw new SystemFailureException;
        }
    }
}
