<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\BizSusu\BizSusuCreateRequestDTO;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\FrequencyService;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Services\BizSusu\BizSusuCreateService;
use App\Interface\Resources\V1\Susu\IndividualSusu\BizSusu\BizSusuResource;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuCreateAction
{
    private BizSusuCreateService $bizSusuCreateService;

    /**
     * @param CustomerWalletService $customerLinkedWalletService
     * @param SusuSchemeService $susuSchemeService
     * @param FrequencyService $frequencyService
     * @param BizSusuCreateService $bizSusuCreateService
     */
    public function __construct(
        BizSusuCreateService $bizSusuCreateService
    ) {
        $this->bizSusuCreateService = $bizSusuCreateService;
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
        // Build and return the BizSusuCreateDTO
        $requestDTO = BizSusuCreateRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the BizSusuCreateService and return the resource
        $bizSusu = $this->bizSusuCreateService->execute(
            customer: $customer,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The biz susu account is created successfully. Approval required.',
            data: new BizSusuResource(
                resource: $bizSusu->refresh()
            ),
        );
    }
}
