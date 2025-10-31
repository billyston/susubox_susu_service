<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\FlexySusu\FlexySusuCreateDTO;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerLinkedWalletService;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Services\FlexySusu\FlexySusuCreateService;
use App\Interface\Requests\V1\Susu\FlexySusu\FlexySusuCreateRequest;
use App\Interface\Resources\V1\Susu\FlexySusu\FlexySusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuCreateAction
{
    private CustomerLinkedWalletService $customerLinkedWalletService;
    private SusuSchemeService $susuSchemeService;
    private FlexySusuCreateService $flexySusuCreateService;

    public function __construct(
        CustomerLinkedWalletService $customerLinkedWalletService,
        SusuSchemeService $susuSchemeService,
        FlexySusuCreateService $flexySusuCreateService
    ) {
        $this->customerLinkedWalletService = $customerLinkedWalletService;
        $this->susuSchemeService = $susuSchemeService;
        $this->flexySusuCreateService = $flexySusuCreateService;
    }

    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     */
    public function execute(
        Customer $customer,
        FlexySusuCreateRequest $flexySusuCreateRequest
    ): JsonResponse {
        // Build the FlexySusuCreateDTO and return the DTO
        $dto = FlexySusuCreateDTO::fromArray(
            payload: $flexySusuCreateRequest->validated()
        );

        // Execute the CustomerLinkedWalletService and return the resource
        $linked_wallet = $this->customerLinkedWalletService->execute(
            customer: $customer,
            wallet_resource_id: $dto->linked_wallet_id,
            wallet_number: $dto->wallet_number
        );

        // Execute the SusuSchemeService and return the resource
        $susu_scheme = $this->susuSchemeService->execute(
            scheme_code: config(key: 'susubox.susu_schemes.flexy_susu_code')
        );

        // Execute the FlexySusuCreateService and return the resource
        $flexy_susu = $this->flexySusuCreateService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme,
            linked_wallet: $linked_wallet,
            dto: $dto
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The flexy susu account is created pending approval.',
            data: new FlexySusuResource(
                resource: $flexy_susu->refresh()
            ),
        );
    }
}
