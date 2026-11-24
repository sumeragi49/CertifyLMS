<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Plan;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublishTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_publishes_draft_plan(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->draft()->create();

        $response = $this->actingAs($admin)->post(route('admin.plans.publish', $plan));

        $response->assertRedirect(route('admin.plans.show', $plan));
        $this->assertSame('published', $plan->fresh()->status->value);
        $this->assertDatabaseHas('plans',[
            'status' => 'published',
        ]);
    }

    public function test_cannot_publish_archived_plan(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->archived()->create();

        $response = $this->actingAs($admin)->postJson(route('admin.plans.publish', $plan));

        $this->assertDatabaseMissing('plans',[
            'status' => 'published',
        ]);
    }

    public function test_coach_cannot_publish(): void
    {
        $coach = User::factory()->coach()->create();
        $plan = Plan::factory()->draft()->create();

        $response = $this->actingAs($coach)->post(route('admin.plans.publish', $plan));

        $response->assertForbidden();
    }

    public function test_student_cannot_publish(): void
    {
        $student = User::factory()->student()->create();
        $plan = Plan::factory()->draft()->create();

        $response = $this->actingAs($student)->post(route('admin.plans.publish', $plan));

        $response->assertForbidden();
    }
}
