<?php

declare(strict_types=1);

use App\Events\WalletBalanceUpdated;
use App\Http\Controllers\Api\V1\SendMoneyController;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\BalanceTooLow;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

test('send money to a friend', function () {
    $user = User::factory()
        ->has(Wallet::factory()->richChillGuy())
        ->create();

    $recipient = User::factory()
        ->has(Wallet::factory())
        ->create();

    actingAs($user);

    postJson(action(SendMoneyController::class), [
        'recipient_email' => $recipient->email,
        'amount' => 100,
        'reason' => 'Just a chill guy gift',
    ])
        ->assertNoContent(201);

    expect($recipient->refresh()->wallet->balance)->toBe(100);

    assertDatabaseHas('wallet_transfers', [
        'amount' => 100,
        'source_id' => $user->wallet->id,
        'target_id' => $recipient->wallet->id,
    ]);

    assertDatabaseCount('wallet_transactions', 3);
});

test('cannot send money to a friend with insufficient balance', function () {
    $user = User::factory()
        ->has(Wallet::factory())
        ->create();

    $recipient = User::factory()
        ->has(Wallet::factory())
        ->create();

    actingAs($user);

    postJson(action(SendMoneyController::class), [
        'recipient_email' => $recipient->email,
        'amount' => 100,
        'reason' => 'Just a chill guy gift',
    ])
        ->assertBadRequest()
        ->assertJson([
            'code' => 'INSUFFICIENT_BALANCE',
            'message' => 'Insufficient balance in wallet.',
        ]);

    expect($recipient->refresh()->wallet->balance)->toBe(0);
    
});


test('send notification when balance is under 100', function () {
    Notification::fake();

    $user = User::factory()
        ->has(Wallet::factory()->richChillGuy())
        ->create();

    $recipient = User::factory()
        ->has(Wallet::factory())
        ->create();

    actingAs($user);

    postJson(action(SendMoneyController::class), [
        'recipient_email' => $recipient->email,
        'amount' => 999_901,
        'reason' => 'Just a chill guy gift',
    ])
        ->assertNoContent(201);

    expect($recipient->refresh()->wallet->balance)->toBe(999_901);
    
    assertDatabaseHas('wallet_transfers', [
        'amount' => 999_901,
        'source_id' => $user->wallet->id,
        'target_id' => $recipient->wallet->id,
    ]);

    Notification::assertSentTo(
        [$user], BalanceTooLow::class
    );

});
