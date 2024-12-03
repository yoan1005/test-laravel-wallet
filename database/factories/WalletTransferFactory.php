<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\WalletTransfer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WalletTransfer>
 */
class WalletTransferFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount' => fake()->numberBetween(1, 100),
        ];
    }

    public function amount(int $amount): self
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount,
        ]);
    }
}
