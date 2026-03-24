<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\PaymentInstruction\Services\Settlement\SettlementCancelService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementCancelAction
{
    private SettlementCancelService $settlementCancelService;

    /**
     * @param SettlementCancelService $settlementCancelService
     */
    public function __construct(
        SettlementCancelService $settlementCancelService,
    ) {
        $this->settlementCancelService = $settlementCancelService;
    }

    /**
     * @param Settlement $settlement
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Settlement $settlement,
    ): JsonResponse {
        // Execute the PaymentInstructionCancelService and return the resource
        $this->settlementCancelService->execute(
            settlement: $settlement,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account settlement process has been canceled successfully.',
        );
    }
}
