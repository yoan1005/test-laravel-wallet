<?php

declare(strict_types=1);

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\assertAuthenticated;

test('registration screen can be rendered', function () {
    $response = get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    assertAuthenticated();

    assert(Auth::user()->wallet()->exists());

    $response->assertRedirect(route('dashboard', absolute: false));
});
