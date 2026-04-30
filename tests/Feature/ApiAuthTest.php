<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a user can register and receive an api token', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Andrea',
        'email' => 'andrea@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'device_name' => 'iPhone 16',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('user.name', 'Andrea')
        ->assertJsonPath('user.email', 'andrea@example.com');

    expect($response->json('token'))->not->toBeEmpty();
    expect(User::query()->where('email', 'andrea@example.com')->exists())->toBeTrue();
});

test('an authenticated user can fetch their profile', function () {
    $user = User::factory()->create();
    $token = $user->createToken('Pixel 10')->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/me')
        ->assertSuccessful()
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.email', $user->email);
});
