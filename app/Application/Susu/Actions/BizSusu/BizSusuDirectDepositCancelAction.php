<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Services\PaymentInstructionStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuDirectDepositCancelAction
{
    private PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService;

    public function __construct(
        PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService
    ) {
        $this->paymentInstructionStatusUpdateService = $paymentInstructionStatusUpdateService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        BizSusu $bizSusu,
        PaymentInstruction $paymentInstruction,
        array $request
    ): JsonResponse {
        // Execute the PaymentInstructionStatusUpdateService and return the resource
        $this->paymentInstructionStatusUpdateService->execute(
            paymentInstruction: $paymentInstruction,
            status: Statuses::CANCELLED->value,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The direct deposit process has been canceled successfully.',
        );
    }
}
