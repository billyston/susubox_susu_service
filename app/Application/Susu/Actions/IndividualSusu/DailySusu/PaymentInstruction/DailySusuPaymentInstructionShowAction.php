<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\PaymentInstruction;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionShowService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\PaymentInstruction\PaymentInstructionResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuPaymentInstructionShowAction
{
    use Relationships;

    private PaymentInstructionShowService $paymentInstructionShowService;

    /**
     * @param PaymentInstructionShowService $paymentInstructionShowService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        PaymentInstructionShowService $paymentInstructionShowService
    ) {
        $this->paymentInstructionShowService = $paymentInstructionShowService;
        $this->relationships = Helpers::includeResources();
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param PaymentInstruction $paymentInstruction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        PaymentInstruction $paymentInstruction
    ): JsonResponse {
        // Execute the PaymentInstructionShowService and return resource
        $paymentInstruction = $this->paymentInstructionShowService->execute(
            customer: $customer,
            account: $dailySusu->account,
            paymentInstruction: $paymentInstruction
        );
        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $paymentInstruction->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new PaymentInstructionResource(
                resource: $paymentInstruction,
            ),
        );
    }
}
