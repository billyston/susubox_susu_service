<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\TransactionCreateDTO;
use App\Application\Transaction\Jobs\TransactionCreatedJob;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Services\CustomerLinkedWalletByNumberService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Services\TransactionCategoryByCodeGetService;
use App\Domain\Transaction\Services\TransactionCreateService;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TransactionCreateAction
{
    private TransactionCreateService $transactionCreateService;
    private TransactionCategoryByCodeGetService $transactionCategoryByCodeGetService;
    private CustomerLinkedWalletByNumberService $customerLinkedWalletByNumberService;

    public function __construct(
        TransactionCreateService $transactionCreateService,
        TransactionCategoryByCodeGetService $transactionCategoryByCodeGetService,
        CustomerLinkedWalletByNumberService $customerLinkedWalletByNumberService
    ) {
        $this->transactionCreateService = $transactionCreateService;
        $this->transactionCategoryByCodeGetService = $transactionCategoryByCodeGetService;
        $this->customerLinkedWalletByNumberService = $customerLinkedWalletByNumberService;
    }

    /**
     * @throws SystemFailureException
     * @throws NumberFormatException
     * @throws RoundingNecessaryException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Account $account,
        Request $request,
    ): JsonResponse {
        // Build the TransactionCreateDTO and return the DTO
        $dto = TransactionCreateDTO::fromArray(
            payload: $request->all()
        );

        // Execute the TransactionCreateService and return the Transaction resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            code: $dto->transaction_category,
        );

        // Execute the CustomerLinkedWalletByNumberService and return the wallet resource
        $wallet = $this->customerLinkedWalletByNumberService->execute(
            wallet_number: $dto->wallet_number,
        );

        // Execute the TransactionCreateService and return the Transaction resource
        $transaction = $this->transactionCreateService->execute(
            account: $account,
            linkedWallet: $wallet,
            transactionCategory: $transactionCategory,
            data: $dto
        );

        // Dispatch the TransactionCreatedJob
        TransactionCreatedJob::dispatch(
            transaction: $transaction
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_ACCEPTED,
            message: 'Request accepted',
            description: 'The request was accepted for processing',
        );
    }
}
