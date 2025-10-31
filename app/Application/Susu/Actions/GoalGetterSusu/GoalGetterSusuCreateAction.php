<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\GoalGetterSusu\GoalGetterSusuCreateDTO;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerLinkedWalletService;
use App\Domain\Shared\Exceptions\FrequencyNotFoundException;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\FrequencyService;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Services\GoalGetterSusu\GoalGetterSusuCreateService;
use App\Interface\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuCreateRequest;
use App\Interface\Resources\V1\Susu\GoalGetterSusu\GoalGetterSusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuCreateAction
{
    private CustomerLinkedWalletService $customerLinkedWalletService;
    private SusuSchemeService $susuSchemeService;
    private FrequencyService $frequencyService;
    private GoalGetterSusuCreateService $goalGetterSusuCreateService;

    public function __construct(
        CustomerLinkedWalletService $customerLinkedWalletService,
        SusuSchemeService $susuSchemeService,
        FrequencyService $frequencyService,
        GoalGetterSusuCreateService $goalGetterSusuCreateService
    ) {
        $this->customerLinkedWalletService = $customerLinkedWalletService;
        $this->susuSchemeService = $susuSchemeService;
        $this->frequencyService = $frequencyService;
        $this->goalGetterSusuCreateService = $goalGetterSusuCreateService;
    }

    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws FrequencyNotFoundException
     */
    public function execute(
        Customer $customer,
        GoalGetterSusuCreateRequest $goalGetterSusuCreateRequest
    ): JsonResponse {
        // Build the GoalGetterSusuCreateDTO and return the DTO
        $dto = GoalGetterSusuCreateDTO::fromArray(
            payload: $goalGetterSusuCreateRequest->validated()
        );

        // Execute the CustomerLinkedWalletService and return the resource
        $linked_wallet = $this->customerLinkedWalletService->execute(
            customer: $customer,
            wallet_resource_id: $dto->linked_wallet_id,
            wallet_number: $dto->wallet_number
        );

        // Execute the SusuSchemeService and return the resource
        $susu_scheme = $this->susuSchemeService->execute(
            scheme_code: config(key: 'susubox.susu_schemes.goal_getter_susu_code')
        );

        // Execute the FrequencyService and return the resource
        $frequency = $this->frequencyService->execute(
            frequency_code: $dto->frequency
        );

        // Execute the GoalGetterSusuCreateService and return the resource
        $goal_getter_susu = $this->goalGetterSusuCreateService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme,
            frequency: $frequency,
            linked_wallet: $linked_wallet,
            dto: $dto
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The goal getter susu account is created pending approval.',
            data: new GoalGetterSusuResource(
                resource: $goal_getter_susu->refresh()
            ),
        );
    }
}
