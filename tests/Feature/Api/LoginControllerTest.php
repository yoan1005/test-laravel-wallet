<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\LoginController;
use App\Models\User;

use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertCount;

test('login should return token', function () {
    $user = User::factory()->create(['email' => 'test@test.com']);

    postJson(action(LoginController::class), [
        'email' => 'test@test.com',
        'password' => 'password',
        'device_name' => 'Feature test',
    ])
        ->assertCreated()
        ->assertJsonStructure(['data' => ['token']]);

    assertCount(1, $user->refresh()->tokens);
});

test('bad login should return HTTP 400', function () {
    postJson(action(LoginController::class), [
        'email' => 'test@test.com',
        'password' => 'password',
        'device_name' => 'Feature test',
    ])
        ->assertStatus(400)
        ->assertJsonPath('message', 'Invalid credentials.')
        ->assertJsonPath('code', 'BAD_LOGIN');
});
