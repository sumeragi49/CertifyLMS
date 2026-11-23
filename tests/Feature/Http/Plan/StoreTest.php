<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Plan;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    
    private function payload(array $override = []): array
    {
        $plan = Plan::factory()->create();

        return array_merge([
            'name' => '4ヶ月プラン16回',
            'description' => '面談は基本で16回です。',
            'duration_days' => 120,
            'default_meeting_quota' => 16,
            'status' => 'draft',
            'sort_order' => 16,
        ], $override);
    }

    public function test_admin_can_create_plan_as_draft(): void
    {
        $admin = User::factory()->admin()->create();
        $storePlan = $this->payload();

        $response = $this->actingAs($admin)->post(route('admin.plans.store'), $storePlan);

        $response->assertRedirect();
        $this->assertDatabaseHas('plans', [
            'name' => '4ヶ月プラン16回',
            'description' => '面談は基本で16回です。',
            'duration_days' => 120,
            'default_meeting_quota' => 16,
            'status' => 'draft',
            'sort_order' => 16,
            'created_by_user_id' => $admin->id,
            'updated_by_user_id' => $admin->id,
        ]);
    }

    public function test_required_fields_are_validated(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.plans.store'), $this->payload(['name' => '']))
            ->assertSessionHasErrors('name');

        $this->actingAs($admin)
            ->post(route('admin.plans.store'), $this->payload(['duration_days' => '']))
            ->assertSessionHasErrors('duration_days');

        $this->actingAs($admin)
            ->post(route('admin.plans.store'), $this->payload(['default_meeting_quota' => '']))
            ->assertSessionHasErrors('default_meeting_quota');

        $this->actingAs($admin)
            ->post(route('admin.plans.store'), $this->payload(['sort_order' => '']))
            ->assertSessionHasErrors('sort_order');
    }

     public function test_coach_cannot_create_plan(): void
    {
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->post(route('admin.plans.store'), $this->payload());

        $response->assertForbidden();
    }

    public function test_student_cannot_create_plan(): void
    {
        $student = User::factory()->coach()->create();

        $response = $this->actingAs($student)->post(route('admin.plans.store'), $this->payload());

        $response->assertForbidden();
    }
}
