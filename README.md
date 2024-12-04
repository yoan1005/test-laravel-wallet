# Test Technique - Laravel Wallet

![Screenshot](screenshot.png)

## Requirements

- PHP 8.2+
- Composer

(i) No need database, just use sqlite.

## Installation

```bash
composer install
```

```bash
php artisan key:generate
```

```bash
php artisan migrate:fresh --seed
```

```bash
npm install
```

```bash
npm run dev
```

```bash
php artisan serve
```

## Running tests

```bash
vendor/bin/pest
```

## Running formatter

```bash
vendor/bin/pint
```

## Default Users

Rich chill guy (1M€ balance):
- Email: rich.chill.guy@test.fr
- Password: password

Another guy (0€ balance):
- Email: another.guy@test.fr
- Password: password

## Database schema

![Database schema](mcd.png)

Amounts are saved in cents.

Some Laravel tables are included : 
- sessions
- cache
- cache_locks
- jobs
- job_batches
- failed_jobs

## Tickets

⚠️ Tests, factories & seeders are mandatory/required

### Bugfix - Error on login

On first login after registering, this error is thrown:
```
Call to a member function transactions() on null
```

### Feature - Notification when balance is low

When a user balance is low (< 10€), he should be notified by email.

### Feature - Recurring transfers

As a user, I want to be able to create recurring transfers.

I want to define : 
- A start date
- An end date
- A frequency in days
- An amount
- A reason

Of course, I want to be able to delete a recurring transfer.

The transfer must be executed every X days (according to the periodicity defined by the user) at 2:00 a.m.
The transfer must also be executed immediately after its creation.

If the user has a balance lower than the amount, the transfer should fail and the user should be notified by email.

This feature must be available in the API and the front-end.

### BONUS - Feature - Taking out a loan

As a user I can ask to take out a loan.

**The credit is granted automatically if the following conditions are met:**

0-200 € :
- If, over a 3-month period, the average account balance was above €100.

200-500 € :
- If, over a 3-month period, the average account balance was over €300;
- and the user has made at least 5 transfers over the same period.

500-1000 € :
- If over a 3-month period, the average account balance was above €500;
- and the user has made at least 1 transfer representing at least 50% of this average balance over the last six months.

Once the credit has been taken out, a recurring transfer (spread over 3 months) which the user cannot delete must be created, with a periodicity of 30 days.
