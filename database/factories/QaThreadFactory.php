<?php

namespace Database\Factories;

use App\Enums\QaThreadStatus;
use App\Enums\TermType;
use App\Models\Certificate;
use App\Models\Certification;
use App\Models\QaThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QaThread>
 */
class QaThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'certification_id' => Certification::factory()
                               -> published(),
            'title' => fake()->sentence(1),
            'body' => fake()->sentence(4),
            'status' => QaThreadStatus::Unresolved->value,
            'resolved_at' => now()->addMonths(3)->toDateString(),
        ];
    }

    public function createCertificate(): static
    {
        return $this->afterCreating(function (QaThread $qaThread) {
            Certificate::factory()->create([
                'user_id' => $qaThread['user_id'],
                'certification_id' => $qaThread['certification_id'],
            ]);
        });
    }

    public function resolved(): static
    {
        return $this->state(fn () => [
            'status' => QaThreadStatus::Resolved->value,
            'resolved_at' => now()->addMonths(3),
        ]);
    }
}
