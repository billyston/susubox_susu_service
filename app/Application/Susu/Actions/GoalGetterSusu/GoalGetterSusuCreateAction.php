<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\GoalGetterSusu\GoalGetterSusuCreateRequestDTO;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Services\GoalGetterSusu\GoalGetterSusuCreateService;
use App\Interface\Resources\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuResource;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuCreateAction
{
    private GoalGetterSusuCreateService $goalGetterSusuCreateService;

    /**
     * @param GoalGetterSusuCreateService $goalGetterSusuCreateService
     */
    public function __construct(
        GoalGetterSusuCreateService $goalGetterSusuCreateService
    ) {
        $this->goalGetterSusuCreateService = $goalGetterSusuCreateService;
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
        // Build and return the GoalGetterSusuCreateRequestDTO
        $requestDTO = GoalGetterSusuCreateRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the GoalGetterSusuCreateService and return the resource
        $goalGetterSusu = $this->goalGetterSusuCreateService->execute(
            customer: $customer,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The goal getter susu account is created successfully. Approval required.',
            data: new GoalGetterSusuResource(
                resource: $goalGetterSusu->refresh()
            ),
        );
    }
}
