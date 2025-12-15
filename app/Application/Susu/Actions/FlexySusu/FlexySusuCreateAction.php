<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\FlexySusu\FlexySusuCreateDTO;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletService;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Services\FlexySusu\FlexySusuCreateService;
use App\Interface\Resources\V1\Susu\IndividualSusu\FlexySusu\FlexySusuResource;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuCreateAction
{
    private CustomerWalletService $customerLinkedWalletService;
    private SusuSchemeService $susuSchemeService;
    private FlexySusuCreateService $flexySusuCreateService;

    public function __construct(
        CustomerWalletService $customerLinkedWalletService,
        SusuSchemeService $susuSchemeService,
        FlexySusuCreateService $flexySusuCreateService
    ) {
        $this->customerLinkedWalletService = $customerLinkedWalletService;
        $this->susuSchemeService = $susuSchemeService;
        $this->flexySusuCreateService = $flexySusuCreateService;
    }

    /**
     * @param Customer $customer
     * @param array $request
     * @return JsonResponse
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Customer $customer,
        array $request
    ): JsonResponse {
        // Build the FlexySusuCreateDTO and return the DTO
        $dto = FlexySusuCreateDTO::fromArray(
            payload: $request
        );

        // Execute the CustomerWalletService and return the resource
        $linked_wallet = $this->customerLinkedWalletService->execute(
            customer: $customer,
            wallet_resource_id: $dto->wallet_id,
        );

        // Execute the SusuSchemeService and return the resource
        $susu_scheme = $this->susuSchemeService->execute(
            scheme_code: config(key: 'susubox.susu_schemes.flexy_susu_code')
        );

        // Execute the FlexySusuCreateService and return the resource
        $flexy_susu = $this->flexySusuCreateService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme,
            wallet: $linked_wallet,
            dto: $dto->toArray()
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The flexy susu account is created successfully. Approval required.',
            data: new FlexySusuResource(
                resource: $flexy_susu->refresh()
            ),
        );
    }
}
