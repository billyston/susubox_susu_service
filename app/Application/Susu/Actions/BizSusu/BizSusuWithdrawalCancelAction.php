<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCancelService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuWithdrawalCancelAction
{
    private PaymentInstructionCancelService $paymentInstructionCancelService;

    /**
     * @param PaymentInstructionCancelService $paymentInstructionCancelService
     */
    public function __construct(
        PaymentInstructionCancelService $paymentInstructionCancelService,
    ) {
        $this->paymentInstructionCancelService = $paymentInstructionCancelService;
    }

    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param PaymentInstruction $paymentInstruction
     * @param array $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        BizSusu $bizSusu,
        PaymentInstruction $paymentInstruction,
        array $request
    ): JsonResponse {
        // Execute the PaymentInstructionCancelService and return the resource
        $this->paymentInstructionCancelService->execute(
            paymentInstruction: $paymentInstruction,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The direct deposit process has been canceled successfully.',
        );
    }
}
