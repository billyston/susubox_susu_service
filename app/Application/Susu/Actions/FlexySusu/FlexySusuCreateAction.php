<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\FlexySusu\FlexySusuCreateRequestDTO;
use App\Domain\Customer\Exceptions\WalletNotFoundException;
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

    /**
     * @param CustomerWalletService $customerLinkedWalletService
     * @param SusuSchemeService $susuSchemeService
     * @param FlexySusuCreateService $flexySusuCreateService
     */
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
     * @throws WalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Customer $customer,
        array $request
    ): JsonResponse {
        // Build the FlexySusuCreateRequestDTO and return the DTO
        $requestDTO = FlexySusuCreateRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the CustomerWalletService and return the resource
        $wallet = $this->customerLinkedWalletService->execute(
            customer: $customer,
            walletResourceID: $requestDTO->walletResourceID,
        );

        // Execute the SusuSchemeService and return the resource
        $susuScheme = $this->susuSchemeService->execute(
            schemeCode: config(key: 'susubox.susu_schemes.flexy_susu_code')
        );

        // Execute the FlexySusuCreateService and return the resource
        $flexySusu = $this->flexySusuCreateService->execute(
            customer: $customer,
            susuScheme: $susuScheme,
            wallet: $wallet,
            requestDTO: $requestDTO->toArray()
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
