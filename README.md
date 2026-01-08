# SusuBox susu service

[![tests](https://github.com/JustSteveKing/api-kit/actions/workflows/tests.yml/badge.svg)](https://github.com/JustSteveKing/api-kit/actions/workflows/tests.yml)
[![linter](https://github.com/JustSteveKing/api-kit/actions/workflows/lint.yml/badge.svg)](https://github.com/JustSteveKing/api-kit/actions/workflows/lint.yml)

The SusuBox susu service API.

## Todo

## Customer
- [x] Create new customer
- [x] Create new customer wallet
- [x] Get all wallets

## Resources
- [x] Build the frequencies
- [x] Get all frequencies
- [x] Build the durations
- [x] Get all durations
- [x] Build the start dates
- [x] Get all start dates
- [x] Build the susu_schemes
- [x] Get all susu_schemes
- [x] Build the commission_and_charges resource

## Account
- [x] Create and Configure Account model and migration
- [x] Get all customer susu accounts
- [x] Get a susu account for customer
- [x] Get account balance
- [x] Update account status (active) after successful initial debit
- [ ] Work on a unique account_number

## IndividualAccount
- [x] Create IndividualAccount model and migration
- [x] Configure IndividualAccount relations




## DailySusu
- [x] Create DailySusu model and migration
- [x] Configure DailySusu relations

###### Create DailySusu
- [x] Create account
- [x] Cancel create account
- [x] Approve account
- [x] Activate account
- [ ] Validate create account
- [ ] Validate cancel account
- [ ] Validate approve account

###### DailySusu Activation (initial deposit)
- [x] Set recurring_debit_status (active)
- [x] Set account status (active)

###### DailySusu Re-Activation (if initial deposit failed)
- [ ] Initiate and approve account re-activation
- [ ] Validate the account re-activation

###### Get DailySusu
- [x] Get all accounts for customer
- [x] Get single account for customer
- [ ] Validate get account

###### DailySusu Direct Deposit
- [x] Create direct deposit (only in frequencies) 
- [x] Cancel direct deposit 
- [x] Approve direct deposit 
- [ ] Validate create direct deposit
- [ ] Validate cancel direct deposit
- [ ] Validate approve direct deposit

###### DailySusu Settlements
- [ ] Create settle pending
- [ ] Create settle all pending
- [ ] Create zero-out settlement
- [ ] Cancel settlement process
- [ ] Approve settlement
- [ ] Validate create settlement
- [ ] Validate cancel settlement
- [ ] Validate approve settlement

###### DailySusu Lock Account
- [x] Create lock account
- [x] Cancel lock account process
- [x] Approve lock account
- [ ] Validate create lock account
- [ ] Validate cancel lock account
- [ ] Validate approve lock account

###### DailySusu Unlock Account
- [x] Initiate the account_unlock background job

###### DailySusu activate auto settlement
- [ ] Initiate and approve the auto settlement feature
- [ ] Validate the auto settlement
- [ ] Initiate auto settlement (After successful deposit)

###### DailySusu de-activate auto settlement
- [ ] Initiate and approve the de-activate auto settlement
- [ ] Validate the de-activate auto settlement
- [ ] De-activate the auto settlement (Set status to false)

###### DailySusu pause recurring debits
- [ ] Create pause debit
- [ ] Cancel pause debit process
- [ ] Approve pause debit process
- [ ] Validate create pause debit
- [ ] Validate cancel pause debit
- [ ] Validate approve pause debit

###### DailySusu resume recurring debits
- [ ] Initiate and approve resume recurring debits
- [ ] Validate resume recurring debits
- [ ] Implement DailySusu resume recurring debits

###### DailySusu failed debit rollover 
- [ ] Initiate and approve failed debit rollover feature
- [ ] Validate the failed debit rollover action
- [ ] Implement DailySusu failed debit rollover feature

###### DailySusu close account (In consideration)
- [ ] Initiate and approve close DailySusu account
- [ ] Validate the close DailySusu account
- [ ] Implement DailySusu close DailySusu account

###### DailySusu statistics
- [ ] Build all DailySusu statistics
- [ ] Get all DailySusu statistics

###### DailySusu transactions
- [ ] Get all transactions
- [ ] Get single transaction




## BizSusu
- [x] Create BizSusu model and migration
- [x] Configure BizSusu relations

###### Create BizSusu
- [x] Create account
- [x] Cancel create account
- [x] Approve account
- [x] Activate account
- [ ] Validate create account
- [ ] Validate cancel account
- [ ] Validate approve account

###### BizSusu Activation (initial deposit)
- [x] Set recurring_debit_status
- [x] Set account status

###### BizSusu Re-Activation (if initial deposit failed)
- [ ] Initiate and approve account re-activation
- [ ] Validate the account re-activation

###### Get BizSusu
- [x] Get single Biz susu for customer
- [x] Get all Biz susu for customer
- [ ] Validate get BizSusu

###### BizSusu Direct Deposit
- [x] Create direct deposit (in frequencies)
- [x] Create direct deposit (in amount)
- [x] Cancel direct deposit
- [x] Approve direct deposit
- [ ] Validate create direct deposit
- [ ] Validate cancel direct deposit
- [ ] Validate approve direct deposit

###### BizSusu Withdrawal
- [x] Create partial withdrawal
- [x] Create full withdrawal
- [x] Cancel withdrawal process
- [x] Approve withdrawal
- [ ] Validate create withdrawal
- [ ] Validate cancel withdrawal
- [ ] Validate approve withdrawal

###### BizSusu Lock Account
- [x] Create lock account
- [x] Cancel lock account process
- [x] Approve lock account
- [ ] Validate create lock account
- [ ] Validate cancel lock account
- [ ] Validate approve lock account

###### BizSusu Unlock Account
- [x] Initiate the account_unlock background job

###### BizSusu pause recurring debits
- [ ] Create pause debit
- [ ] Cancel pause debit process
- [ ] Approve pause debit process
- [ ] Validate create pause debit
- [ ] Validate cancel pause debit
- [ ] Validate approve pause debit

###### BizSusu resume recurring debits
- [ ] Initiate and approve resume recurring debits
- [ ] Validate resume recurring debits
- [ ] Implement BizSusu resume recurring debits

###### BizSusu failed debit rollover
- [ ] Initiate and approve failed debit rollover feature
- [ ] Validate the failed debit rollover action
- [ ] Implement BizSusu failed debit rollover feature

###### BizSusu close account (In consideration)
- [ ] Initiate and approve close BizSusu account
- [ ] Validate the close BizSusu account
- [ ] Implement BizSusu close BizSusu account

###### BizSusu statistics
- [ ] Build all BizSusu statistics
- [ ] Get all BizSusu statistics

###### BizSusu transactions
- [ ] Get all transactions
- [ ] Get single transaction





## GoalGetterSusu
- [x] Create GoalGetterSusu model and migration
- [x] Configure GoalGetterSusu relations

###### Create GoalGetterSusu
- [x] Create account
- [x] Cancel create account
- [x] Approve account
- [x] Activate account
- [ ] Validate create account
- [ ] Validate cancel account
- [ ] Validate approve account

###### GoalGetterSusu Activation (initial deposit)
- [x] Set recurring_debit_status
- [x] Set account status

###### GoalGetterSusu Re-Activation (if initial deposit failed)
- [ ] Initiate and approve account re-activation
- [ ] Validate the account re-activation

###### Get GoalGetterSusu
- [x] Get single GoalGetterSusu for customer
- [x] Get all GoalGetterSusu for customer
- [ ] Validate get GoalGetterSusu

###### GoalGetterSusu Direct Deposit
- [x] Create direct deposit (in frequencies)
- [x] Create direct deposit (in amount)
- [x] Cancel direct deposit
- [x] Approve direct deposit
- [ ] Validate create direct deposit
- [ ] Validate cancel direct deposit
- [ ] Validate approve direct deposit

###### GoalGetterSusu (Goal completed)
- [ ] Validate and initiate goalCompleted feature

###### GoalGetterSusu Withdrawal
- [x] Create partial withdrawal
- [x] Create full withdrawal
- [x] Cancel withdrawal process
- [x] Approve withdrawal
- [ ] Validate create withdrawal
- [ ] Validate cancel withdrawal
- [ ] Validate approve withdrawal

###### GoalGetterSusu Lock Account
- [x] Create lock account

###### GoalGetterSusu Unlock Account
- [ ] Initiate the unlock background job

###### GoalGetterSusu pause recurring debits
- [ ] Initiate and approve pause recurring debits
- [ ] Validate pause recurring debits
- [ ] Implement GoalGetterSusu pause recurring debits

###### GoalGetterSusu resume recurring debits
- [ ] Initiate and approve resume recurring debits
- [ ] Validate resume recurring debits
- [ ] Implement GoalGetterSusu resume recurring debits

###### GoalGetterSusu failed debit rollover
- [ ] Initiate and approve failed debit rollover feature
- [ ] Validate the failed debit rollover action
- [ ] Implement GoalGetterSusu failed debit rollover feature

###### GoalGetterSusu close account (In consideration)
- [ ] Initiate and approve close GoalGetterSusu account
- [ ] Validate the close GoalGetterSusu account
- [ ] Implement GoalGetterSusu close GoalGetterSusu account

###### GoalGetterSusu statistics
- [ ] Build all GoalGetterSusu statistics
- [ ] Get all GoalGetterSusu statistics

###### GoalGetterSusu transactions
- [ ] Get all transactions
- [ ] Get single transaction




## FlexySusu
- [x] Create FlexySusu model and migration
- [x] Configure FlexySusu relations

###### Create FlexySusu
- [x] Create account
- [x] Cancel create account
- [x] Approve account
- [x] Activate account
- [ ] Validate create account
- [ ] Validate cancel account
- [ ] Validate approve account

###### FlexySusu Activation (initial deposit)
- [x] Set recurring_debit_status
- [x] Set account status

###### FlexySusu Re-Activation (if initial deposit failed)
- [ ] Initiate and approve account re-activation
- [ ] Validate the account re-activation

###### FlexySusu Get
- [x] Get single FlexySusu for customer
- [x] Get all FlexySusu for customer
- [ ] Validate get FlexySusu

###### FlexySusu Direct Deposit
- [x] Create direct deposit (in frequencies)
- [x] Create direct deposit (in amount)
- [x] Cancel direct deposit
- [x] Approve direct deposit
- [ ] Validate create direct deposit
- [ ] Validate cancel direct deposit
- [ ] Validate approve direct deposit

###### FlexySusu Withdrawal
- [x] Create partial withdrawal
- [x] Create full withdrawal
- [x] Cancel withdrawal process
- [ ] Approve withdrawal
- [ ] Validate withdrawal

###### FlexySusu Lock Account
- [x] Create lock account
- [x] Cancel lock account process
- [x] Approve lock account
- [ ] Validate create lock account
- [ ] Validate cancel lock account
- [ ] Validate approve lock account

###### FlexySusu Unlock Account
- [x] Initiate the account_unlock background job

###### FlexySusu pause recurring debits
- [ ] Initiate and approve pause recurring debits
- [ ] Validate pause recurring debits
- [ ] Implement FlexySusu pause recurring debits

###### FlexySusu close account (In consideration)
- [ ] Initiate and approve close FlexySusu account
- [ ] Validate the close FlexySusu account
- [ ] Implement FlexySusu close FlexySusu account

###### FlexySusu statistics
- [ ] Build all FlexySusu statistics
- [ ] Get all FlexySusu statistics

###### FlexySusu transactions
- [ ] Get all transactions
- [ ] Get single transaction




## Transactions
- [x] Create account transaction
- [x] TransactionStatus from Payment Service should match Susu Service 
- [x] All transactions must have a TransactionType (debit or credit)
- [ ] Get all transactions for account
- [ ] Get single transaction for account

## Transactions Post Actions
- [ ] Handle TransactionCreatedSuccessAction
- [ ] Handle initial_deposit
- [ ] Handle subsequent debit
- [ ] Handle susu type actions (process auto settlement, close goal)
- [ ] Dispatch TransactionSuccessNotification


- [ ] Handle TransactionCreatedFailureAction
- [ ] Dispatch TransactionFailureNotification

## PIN Authorization Middleware
- [ ] Implement the PIN Authorization Middleware

## Transaction reconciliation
- [ ] Implement the transaction reconciliation feature

## General
- [ ] Move all jobs that publishes job into the services / shared folder
- [ ] Do not hard code 'service_category' in approval DTOs
- [ ] When payment_instruction is cancelled, approval_status->cancelled, status->terminated
- [ ] Update transaction's payment_instruction->status (active, terminated, success, failed)
- [ ] internal_reference field must be updated after payment_service returned data
- [ ] Rebuild all the Resource files (AccountBalanceResource as an example)
- [ ] Review all the response messages
- [ ] Provide descriptions for all api responses
- [ ] Review all the exceptions and give proper messaging
- [x] All $variable, function and method names should follow CamelCase
- [x] All APIResource, ResponseDTO toArray and DB fields names must follow snake_case
