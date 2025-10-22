<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Transaction\Jobs\TransactionCreatedJob;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Services\CustomerLinkedWalletByNumberService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Services\TransactionCategoryByCodeGetService;
use App\Domain\Transaction\Services\TransactionCreateService;
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
     */
    public function execute(
        Account $account,
        Request $request,
    ): JsonResponse {
        // Extract the account data from the $request
        $request_data = Helpers::extractDataAttributes(
            request_data: $request->all()
        );

        // Extract the linked_wallet data from the $request
        $request_wallet = Helpers::extractIncludedAttributes(
            request_data: data_get($request->all(), 'included.linked_wallet')
        );

        // Execute the TransactionCreateService and return the Transaction resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            code: $request_data['transaction_category'],
        );

        // Execute the CustomerLinkedWalletByNumberService and return the wallet resource
        $wallet = $this->customerLinkedWalletByNumberService->execute(
            wallet_number: $request_wallet['wallet_number'],
        );

        // Execute the TransactionCreateService and return the Transaction resource
        $transaction = $this->transactionCreateService->execute(
            account: $account,
            linkedWallet: $wallet,
            transactionCategory: $transactionCategory,
            request_data: $request_data
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
