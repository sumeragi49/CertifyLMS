<?php

declare(strict_types=1);

namespace Tests\Feature\Http\MeetingPack;

use App\Models\MeetingPack;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_meeting_pack(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->create([
            'name' => '12回パック',
            'description' => '説明文(12)',
            'meeting_count' => 12,
            'price' => 150000,
            'stripe_price_id' => null,
            'sort_order' => 12,
            'updated_by_user_id' => $admin->id,
        ]);

        $payload = [
            'name' => '15回パック',
            'description' => '説明文(15)',
            'meeting_count' => 15,
            'price' => 180000,
            'stripe_price_id' => null,
            'sort_order' => 18,
        ];

        $response = $this->actingAs($admin)->patch(route('admin.meeting-packs.update', $plan), $payload);

        $response->assertRedirect(route('admin.meeting-packs.show', $plan));
        $this->assertDatabaseHas('meeting_packs', [
            'id' => $plan->id,
            'name' => '15回パック',
            'description' => '説明文(15)',
            'meeting_count' => 15,
            'price' => 180000,
            'stripe_price_id' => null,
            'sort_order' => 18,
            'updated_by_user_id' => $admin->id,
        ]);
    }
}
