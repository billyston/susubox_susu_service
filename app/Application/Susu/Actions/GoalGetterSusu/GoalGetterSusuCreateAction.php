<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\GoalGetterSusu\GoalGetterSusuCreateDTO;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletService;
use App\Domain\Shared\Exceptions\FrequencyNotFoundException;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\FrequencyService;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Services\GoalGetterSusu\GoalGetterSusuCreateService;
use App\Interface\Resources\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuResource;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuCreateAction
{
    private CustomerWalletService $customerLinkedWalletService;
    private SusuSchemeService $susuSchemeService;
    private FrequencyService $frequencyService;
    private GoalGetterSusuCreateService $goalGetterSusuCreateService;

    public function __construct(
        CustomerWalletService $customerLinkedWalletService,
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
     * @param Customer $customer
     * @param array $request
     * @return JsonResponse
     * @throws FrequencyNotFoundException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Customer $customer,
        array $request
    ): JsonResponse {
        // Build the GoalGetterSusuCreateDTO and return the DTO
        $dto = GoalGetterSusuCreateDTO::fromArray(
            payload: $request
        );

        // Execute the CustomerWalletService and return the resource
        $linked_wallet = $this->customerLinkedWalletService->execute(
            customer: $customer,
            wallet_resource_id: $dto->wallet_id,
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
            wallet: $linked_wallet,
            dto: $dto->toArray()
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The goal getter susu account is created successfully. Approval required.',
            data: new GoalGetterSusuResource(
                resource: $goal_getter_susu->refresh()
            ),
        );
    }
}
