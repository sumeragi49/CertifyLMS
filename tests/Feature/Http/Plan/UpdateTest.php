<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Plan;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_plan(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->create([
            'name' => '4ヵ月プラン16回',
            'description' => '面談回数16回のプランです。',
            'duration_days' => 16,
            'default_meeting_quota' => 16,
            'sort_order' => 16,
            'updated_by_user_id' => $admin->id,
        ]);

        $payload = [
            'name' => '9ヵ月プラン36回',
            'description' => '面談回数36回のプランです。',
            'duration_days' => 270,
            'default_meeting_quota' => 36,
            'sort_order' => 36,
            'updated_by_user_id' => $admin->id,
        ];

        $response = $this->actingAs($admin)->put(route('admin.plans.update', $plan), $payload);

        $response->assertRedirect(route('admin.plans.show', $plan));
        $this->assertDatabaseHas('plans', [
            'name' => '9ヵ月プラン36回',
            'description' => '面談回数36回のプランです。',
            'duration_days' => 270,
            'default_meeting_quota' => 36,
            'sort_order' => 36,
            'updated_by_user_id' => $admin->id,
        ]);
    }
}
