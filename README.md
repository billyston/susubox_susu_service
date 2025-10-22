# SusuBox susu service

[![tests](https://github.com/JustSteveKing/api-kit/actions/workflows/tests.yml/badge.svg)](https://github.com/JustSteveKing/api-kit/actions/workflows/tests.yml)
[![linter](https://github.com/JustSteveKing/api-kit/actions/workflows/lint.yml/badge.svg)](https://github.com/JustSteveKing/api-kit/actions/workflows/lint.yml)

The SusuBox susu service API.

## Todo

## Customer
- [x] Create new customer
- [x] Linked new customer wallet
- [x] Get all linked wallets

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
- [ ] Get account balance
- [x] Update account status (active) after successful initial debit


## Daily Susu
- [x] Create and configure model and migration

###### Create
- [x] Create daily susu account
- [ ] Validate create daily susu
- [x] Cancel create daily susu
- [x] Approve create daily susu
- [ ] Activate create daily susu

###### Update / Cancel
- [ ] Update daily susu create
- [ ] Validate update daily susu create
- [x] Cancel daily susu create
- [ ] Validate cancel daily susu create
- [ ] Update recurring_debit_status

###### Get
- [x] Get single daily susu for customer
- [x] Get all daily susu for customer

###### Stats
- [ ] Get all statistics for daily susu


## Biz Susu
- [x] Create and configure model and migration

###### Create
- [x] Create biz susu account
- [ ] Validate create biz susu
- [x] Cancel create biz susu
- [x] Approve create biz susu
- [ ] Activate create biz susu

###### Update / Cancel
- [ ] Update biz susu create
- [ ] Validate update biz susu create
- [x] Cancel biz susu create
- [ ] Validate cancel biz susu create
- [ ] Update recurring_debit_status

###### Get
- [x] Get single biz susu for customer
- [x] Get all biz susu for customer

###### Stats
- [ ] Get all statistics for biz susu


## Goal Getter Susu
- [x] Create and configure model and migration

###### Create
- [x] Create goal getter susu account
- [ ] Validate create goal getter susu
- [x] Cancel create goal getter susu
- [x] Approve create goal getter susu
- [ ] Activate create goal getter susu

###### Update / Cancel
- [ ] Update goal getter susu create
- [ ] Validate update goal getter susu create
- [x] Cancel goal getter susu create
- [ ] Validate cancel goal getter susu create
- [ ] Update recurring_debit_status

###### Get
- [x] Get single goal getter susu for customer
- [x] Get all goal getter susu for customer

###### Stats
- [ ] Get all statistics for goal getter susu


## Flexy Susu
- [x] Create and configure model and migration

###### Create
- [x] Create flexy susu account
- [ ] Validate create flexy susu
- [x] Cancel create flexy susu
- [x] Approve create flexy susu
- [ ] Activate create flexy susu

###### Update / Cancel
- [ ] Update flexy susu create
- [ ] Validate update flexy susu create
- [x] Cancel flexy susu create
- [ ] Validate cancel flexy susu create

###### Get
- [x] Get single flexy susu for customer
- [x] Get all flexy susu for customer

###### Stats
- [ ] Get all statistics for flexy susu


## Transactions
- [x] Create account transaction
- [ ] TransactionStatus from Payment Service should match Susu Service 


## Others
- [ ] Move all jobs that publishes job into the services/shared folder 
