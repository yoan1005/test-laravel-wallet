<?php

declare(strict_types=1);

use App\Models\WalletTransaction;

test('that transfers transactions are correctly identified as transfers', function () {
    $transaction = WalletTransaction::factory()
        ->credit()
        ->amount(100)
        ->make(['transfer_id' => 1]);

    expect($transaction->is_transfer)->toBeTrue();
});

test('that classic transactions aren\'t identified as transfers', function () {
    $transaction = WalletTransaction::factory()
        ->credit()
        ->amount(100)
        ->make();

    expect($transaction->is_transfer)->toBeFalse();
});
