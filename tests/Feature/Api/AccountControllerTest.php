<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AccountController;
use App\Models\User;
use App\Models\Wallet;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('get account data', function () {
    $user = User::factory()
        ->has(Wallet::factory()->richChillGuy())
        ->create(['name' => 'John Doe', 'email' => 'test@test.com']);

    actingAs($user);

    getJson(action(AccountController::class))
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => 'John Doe',
                'email' => $user->email,
                'balance' => 1_000_000,
            ],
        ]);
});

test('must be authenticated to get account data', function () {
    getJson(action(AccountController::class))
        ->assertUnauthorized();
});
