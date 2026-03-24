<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\PaymentInstruction;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionIndexService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\PaymentInstruction\PaymentInstructionResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuPaymentInstructionIndexAction
{
    use Relationships;

    private PaymentInstructionIndexService $paymentInstructionIndexService;

    /**
     * @param PaymentInstructionIndexService $paymentInstructionIndexService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        PaymentInstructionIndexService $paymentInstructionIndexService
    ) {
        $this->paymentInstructionIndexService = $paymentInstructionIndexService;
        $this->relationships = Helpers::includeResources();
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
    ): JsonResponse {
        // Execute the PaymentInstructionIndexService and return the collection
        $paymentInstruction = $this->paymentInstructionIndexService->execute(
            customer: $customer,
            account: $dailySusu->account,
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $paymentInstruction->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: PaymentInstructionResource::collection(
                resource: $paymentInstruction
            ),
        );
    }
}
