<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Plan;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArchiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_archives_published_meeting_pack(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->published()->create();

        $response = $this->actingAs($admin)->post(route('admin.plans.archive', $plan));

        $response->assertRedirect(route('admin.plans.show', $plan));
        $this->assertSame('archived', $plan->fresh()->status->value);
        $this->assertDatabaseHas('plans',[
            'status' => 'archived',
        ]);
    }

    public function test_cannot_draft_archived(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->draft()->create();

        $response = $this->actingAs($admin)->post(route('admin.plans.archive', $plan));

        $this->assertDatabaseMissing('plans',[
            'status' => 'archived',
        ]);
    }

    public function test_coach_cannot_archive(): void
    {
        $coach = User::factory()->coach()->create();
        $plan = Plan::factory()->published()->create();

        $response = $this->actingAs($coach)->post(route('admin.plans.archive', $plan));

        $response->assertForbidden();
    }

    public function test_student_cannot_archive(): void
    {
        $student = User::factory()->student()->create();
        $plan = Plan::factory()->published()->create();

        $response = $this->actingAs($student)->post(route('admin.plans.archive', $plan));

        $response->assertForbidden();
    }
}
