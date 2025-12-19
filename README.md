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
- [x] Work on a unique account_number
- [x] Get account balance
- [x] Update account status (active) after successful initial debit


## Daily Susu
- [x] Create and configure model and migration

###### Create
- [x] Create daily susu account
- [ ] Validate create daily susu
- [x] Cancel create daily susu
- [x] Approve create daily susu
- [x] Activate create daily susu

###### Update / Cancel
- [x] Update daily susu create
- [ ] Validate update daily susu create
- [x] Cancel daily susu create
- [ ] Validate cancel daily susu create
- [x] Update recurring_debit_status

###### Get
- [x] Get single daily susu for customer
- [x] Get all daily susu for customer

###### Direct Deposit
- [x] Create direct deposit 
- [x] Cancel direct deposit 
- [x] Approve direct deposit 
- [ ] Validate direct deposit

###### Account Activation
- [ ] Implement account activation (if initial_debit failed)
- [ ] Validate account activation

###### Stats
- [ ] Get all statistics for daily susu


## Biz Susu
- [x] Create and configure model and migration

###### Create
- [x] Create biz susu account
- [ ] Validate create biz susu
- [x] Cancel create biz susu
- [x] Approve create biz susu
- [x] Activate create biz susu

###### Update / Cancel
- [x] Update biz susu create
- [ ] Validate update biz susu create
- [x] Cancel biz susu create
- [ ] Validate cancel biz susu create
- [x] Update recurring_debit_status

###### Get
- [x] Get single biz susu for customer
- [x] Get all biz susu for customer

###### Direct Deposit
- [x] Create direct deposit
- [x] Cancel direct deposit
- [x] Approve direct deposit 
- [ ] Validate direct deposit

###### Account Activation
- [ ] Implement account activation (if initial_debit failed)
- [ ] Validate account activation

###### Stats
- [ ] Get all statistics for biz susu


## Goal Getter Susu
- [x] Create and configure model and migration

###### Create
- [x] Create goal getter susu account
- [ ] Validate create goal getter susu
- [x] Cancel create goal getter susu
- [x] Approve create goal getter susu
- [x] Activate create goal getter susu

###### Update / Cancel
- [x] Update goal getter susu create
- [ ] Validate update goal getter susu create
- [x] Cancel goal getter susu create
- [ ] Validate cancel goal getter susu create
- [x] Update recurring_debit_status

###### Get
- [x] Get single goal getter susu for customer
- [x] Get all goal getter susu for customer

###### Direct Deposit
- [x] Create direct deposit
- [x] Cancel direct deposit
- [x] Approve direct deposit
- [ ] Validate direct deposit

###### Account Activation
- [ ] Implement account activation (if initial_debit failed)
- [ ] Validate account activation

###### Stats
- [ ] Get all statistics for goal getter susu


## Flexy Susu
- [x] Create and configure model and migration

###### Create
- [x] Create flexy susu account
- [ ] Validate create flexy susu
- [x] Cancel create flexy susu
- [x] Approve create flexy susu
- [x] Activate create flexy susu

###### Update / Cancel
- [x] Update flexy susu create
- [ ] Validate update flexy susu create
- [x] Cancel flexy susu create
- [ ] Validate cancel flexy susu create

###### Get
- [x] Get single flexy susu for customer
- [x] Get all flexy susu for customer

###### Direct Deposit
- [x] Create direct deposit
- [x] Cancel direct deposit
- [x] Approve direct deposit
- [ ] Validate direct deposit

###### Account Activation
- [ ] Implement account activation (if initial_debit failed)
- [ ] Validate account activation

###### Stats
- [ ] Get all statistics for flexy susu


## Transactions
- [x] Create account transaction
- [x] TransactionStatus from Payment Service should match Susu Service 
- [x] All transactions must have a TransactionType (debit or credit)
- [ ] Handle TransactionCreatedFailureAction


## General
- [ ] Move all jobs that publishes job into the services / shared folder
- [ ] Do not hard code 'service_category' in approval DTOs
- [ ] When payment_instruction is cancelled, approval_status->cancelled, status->terminated
- [ ] When transaction is created, update the payment_instruction->status (active, terminated, success, failed)
- [ ] internal_reference field must be updated after payment_service returned data
- [ ] Transaction notification (SMS / Email) should have balance updates
- [ ] Rebuild all the Resource files (AccountBalanceResource as an example)
- [ ] Provide descriptions for all api responses

- [ ] Review all the exceptions and give proper messaging
- [ ] Review all the response messages
- [ ] All $variable, function and method names should follow CamelCase
- [ ] All APIResource, ResponseDTO toArray and DB fields names must follow snake_case 
