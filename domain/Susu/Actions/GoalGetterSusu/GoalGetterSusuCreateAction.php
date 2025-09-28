<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\GoalGetterSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Common\Helpers\Helpers;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuCreateRequest;
use Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use Domain\Customer\Models\Customer;
use Domain\Customer\Services\CustomerLinkedWalletService;
use Domain\Shared\Exceptions\FrequencyNotFoundException;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Services\Frequency\FrequencyService;
use Domain\Shared\Services\Scheme\SusuSchemeService;
use Domain\Susu\Data\GoalGetterSusu\GoalGetterSusuResource;
use Domain\Susu\Services\GoalGetterSusu\GoalGetterSusuCreateService;
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
        // Extract the main attributes from the request body
        $request_data = Helpers::extractDataAttributes(
            request_data: $goalGetterSusuCreateRequest->all()
        );

        // Extract the linked_wallet from the request body
        $request_wallet = Helpers::extractIncludedAttributes(
            request_data: data_get($goalGetterSusuCreateRequest->all(), 'data.relationships.linked_wallet')
        );

        // Execute the CustomerLinkedWalletService and return the resource
        $linked_wallet = $this->customerLinkedWalletService->execute(
            customer: $customer,
            wallet_resource_id: $request_wallet['resource_id'],
        );

        // Execute the SusuSchemeService and return the resource
        $susu_scheme = $this->susuSchemeService->execute(
            scheme_code: config(key: 'susubox.susu_schemes.goal_getter_susu_code')
        );

        // Execute the FrequencyService and return the resource
        $frequency = $this->frequencyService->execute(
            frequency_code: $request_data['frequency']
        );

        // Execute the GoalGetterSusuCreateService and return the resource
        $goal_getter_susu = $this->goalGetterSusuCreateService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme,
            frequency: $frequency,
            linked_wallet: $linked_wallet,
            request_data: $request_data
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your goal getter susu account is created pending approval.',
            data: new GoalGetterSusuResource(
                resource: $goal_getter_susu->refresh()
            ),
        );
    }
}
