<?php

declare(strict_types=1);

namespace Tests\Feature\Http\MeetingPack;

use App\Models\MeetingPack;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    
    private function payload(array $override = []): array
    {
        $plan = MeetingPack::factory()->create();

        return array_merge([
            'name' => '12回パック',
            'description' => '説明文',
            'meeting_count' => 12,
            'price' => 150000,
            'stripe_price_id' => null,
            'status' => 'published',
            'sort_order' => 12,
        ], $override);
    }

    public function test_admin_can_create_meeting_pack_as_draft(): void
    {
        $admin = User::factory()->admin()->create();
        $storePlan = $this->payload();

        $response = $this->actingAs($admin)->post(route('admin.meeting-packs.store'), $storePlan);

        $response->assertRedirect();
        $this->assertDatabaseHas('meeting_packs', [
            'name' => '12回パック',
            'description' => '説明文',
            'meeting_count' => 12,
            'price' => 150000,
            'stripe_price_id' => null,
            'status' => 'draft',
            'sort_order' => 12,
            'created_by_user_id' => $admin->id,
            'updated_by_user_id' => $admin->id,
        ]);
    }

    public function test_required_fields_are_validated(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.meeting-packs.store'), $this->payload(['name' => '']))
            ->assertSessionHasErrors('name');

        $this->actingAs($admin)
            ->post(route('admin.meeting-packs.store'), $this->payload(['meeting_count' => '']))
            ->assertSessionHasErrors('meeting_count');

        $this->actingAs($admin)
            ->post(route('admin.meeting-packs.store'), $this->payload(['price' => '']))
            ->assertSessionHasErrors('price');

        $this->actingAs($admin)
            ->post(route('admin.meeting-packs.store'), $this->payload(['sort_order' => '']))
            ->assertSessionHasErrors('sort_order');
    }

    public function test_coach_cannot_create_meeting_pack(): void
    {
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->post(route('admin.meeting-packs.store'), $this->payload());

        $response->assertForbidden();
    }

    public function test_student_cannot_create_meeting_pack(): void
    {
        $student = User::factory()->coach()->create();

        $response = $this->actingAs($student)->post(route('admin.meeting-packs.store'), $this->payload());

        $response->assertForbidden();
    }
}
