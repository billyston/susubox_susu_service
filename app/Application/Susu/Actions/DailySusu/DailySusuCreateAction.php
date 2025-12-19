<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\DailySusu\DailySusuCreateRequestDTO;
use App\Domain\Customer\Exceptions\WalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletService;
use App\Domain\Shared\Exceptions\FrequencyNotFoundException;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\FrequencyService;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Services\DailySusu\DailySusuCreateService;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuResource;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuCreateAction
{
    private CustomerWalletService $customerWalletService;
    private SusuSchemeService $susuSchemeService;
    private FrequencyService $frequencyService;
    private DailySusuCreateService $dailySusuCreateService;

    /**
     * @param CustomerWalletService $customerWalletService
     * @param SusuSchemeService $susuSchemeService
     * @param FrequencyService $frequencyService
     * @param DailySusuCreateService $dailySusuCreateService
     */
    public function __construct(
        CustomerWalletService $customerWalletService,
        SusuSchemeService $susuSchemeService,
        FrequencyService $frequencyService,
        DailySusuCreateService $dailySusuCreateService
    ) {
        $this->customerWalletService = $customerWalletService;
        $this->susuSchemeService = $susuSchemeService;
        $this->frequencyService = $frequencyService;
        $this->dailySusuCreateService = $dailySusuCreateService;
    }

    /**
     * @param Customer $customer
     * @param array $request
     * @return JsonResponse
     * @throws FrequencyNotFoundException
     * @throws WalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Customer $customer,
        array $request
    ): JsonResponse {
        // Build the DailySusuCreateDTO and return the DTO
        $requestDTO = DailySusuCreateRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the CustomerWalletService and return the resource
        $wallet = $this->customerWalletService->execute(
            customer: $customer,
            walletResourceID: $requestDTO->walletResourceID,
        );

        // Execute the SusuSchemeService and return the resource
        $susuScheme = $this->susuSchemeService->execute(
            schemeCode: config(key: 'susubox.susu_schemes.daily_susu_code')
        );

        // Execute the FrequencyService and return the resource
        $frequency = $this->frequencyService->execute(
            frequency_code: 'daily'
        );

        // Execute the DailySusuCreateService and return the resource
        $dailySusu = $this->dailySusuCreateService->execute(
            customer: $customer,
            susuScheme: $susuScheme,
            frequency: $frequency,
            wallet: $wallet,
            requestDTO: $requestDTO->toArray()
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
