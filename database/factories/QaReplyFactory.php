<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\QaThread;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QaReply>
 */
class QaReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->coach(),
            'qa_thread_id' => QaThread::factory()->resolved(),
            'body' => fake()->sentence(4),
        ];
    }
}
