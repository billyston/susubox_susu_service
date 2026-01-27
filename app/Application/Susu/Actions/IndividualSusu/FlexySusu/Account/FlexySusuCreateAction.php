<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\FlexySusu\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\IndividualSusu\FlexySusu\Account\FlexySusuCreateRequestDTO;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Services\IndividualSusu\FlexySusu\Account\FlexySusuCreateService;
use App\Interface\Resources\V1\Susu\IndividualSusu\FlexySusu\FlexySusuResource;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuCreateAction
{
    private FlexySusuCreateService $flexySusuCreateService;

    /**
     * @param FlexySusuCreateService $flexySusuCreateService
     */
    public function __construct(
        FlexySusuCreateService $flexySusuCreateService
    ) {
        $this->flexySusuCreateService = $flexySusuCreateService;
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
        // Build and return the FlexySusuCreateRequestDTO
        $requestDTO = FlexySusuCreateRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the FlexySusuCreateService and return the resource
        $flexySusu = $this->flexySusuCreateService->execute(
            customer: $customer,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The flexy susu account is created successfully. Approval required.',
            data: new FlexySusuResource(
                resource: $flexySusu->refresh()
            ),
        );
    }
}
