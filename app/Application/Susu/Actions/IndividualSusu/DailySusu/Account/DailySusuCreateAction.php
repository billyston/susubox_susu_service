<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Account\DailySusuCreateRequestDTO;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Account\DailySusuCreateService;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuResource;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuCreateAction
{
    private DailySusuCreateService $dailySusuCreateService;

    /**
     * @param DailySusuCreateService $dailySusuCreateService
     */
    public function __construct(
        DailySusuCreateService $dailySusuCreateService
    ) {
        $this->dailySusuCreateService = $dailySusuCreateService;
    }

    /**
     * @param Customer $customer
     * @param array $request
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Customer $customer,
        array $request
    ): JsonResponse {
        // Build and return the DailySusuCreateDTO
        $requestDTO = DailySusuCreateRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the DailySusuCreateService and return the resource
        $dailySusu = $this->dailySusuCreateService->execute(
            customer: $customer,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The daily susu account is created successfully. Approval required.',
            data: new DailySusuResource(
                resource: $dailySusu->refresh()
            ),
        );
    }
}
