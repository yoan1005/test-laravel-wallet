<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\WalletTransactionType;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WalletTransaction>
 */
class WalletTransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount' => fake()->numberBetween(1, 100),
            'type' => fake()->randomElement([
                WalletTransactionType::DEBIT,
                WalletTransactionType::CREDIT,
            ]),
            'reason' => fake()->sentence(),
        ];
    }

    public function credit(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => WalletTransactionType::CREDIT,
            ];
        });
    }

    public function debit(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => WalletTransactionType::DEBIT,
            ];
        });
    }

    public function amount(int $amount): self
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount,
        ]);
    }

    public function reason(string $reason): self
    {
        return $this->state(fn (array $attributes) => [
            'reason' => $reason,
        ]);
    }
}
