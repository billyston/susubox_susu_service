<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Account\DTOs\DirectDepositCreateDTO;
use App\Application\Account\ValueObjects\DirectDepositValueObject;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\DirectDepositCreateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\DailySusu;
use App\Interface\Requests\V1\Susu\DailySusu\DailySusuDirectDepositCreateRequest;
use App\Interface\Resources\V1\Account\DirectDepositResource;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuDirectDepositCreateAction
{
    private DirectDepositCreateService $directDepositCreateService;

    public function __construct(
        DirectDepositCreateService $directDepositCreateService
    ) {
        $this->directDepositCreateService = $directDepositCreateService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Customer $customer,
        DailySusu $daily_susu,
        DailySusuDirectDepositCreateRequest $request
    ): JsonResponse {
        // Build and return the DirectDepositCreateDTO
        $dto = DirectDepositCreateDTO::fromArray(
            payload: $request->all()
        );

        // Compute total deposit amount using the Value Object
        $deposit_values = new DirectDepositValueObject(
            deposit_type: $dto->deposit_type,
            susu_amount: $daily_susu->account->susu_amount,
            frequencies: $dto->frequencies,
            amount: $dto->amount
        );

        // Execute the DirectDepositCreateService and return the DirectDeposit resource
        $direct_deposit = $this->directDepositCreateService->execute(
            account: $daily_susu->account,
            dto: $dto,
            deposit_values: $deposit_values
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The direct deposit was successfully created.',
            data: new DirectDepositResource(
                resource: $direct_deposit->refresh()
            )
        );
    }
}
