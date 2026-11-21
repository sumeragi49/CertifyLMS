<?php

declare(strict_types=1);

namespace Tests\Feature\Http\MeetingPack;

use App\Models\MeetingPack;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublishTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_publishes_draft_meeting_pack(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->draft()->create();

        $response = $this->actingAs($admin)->post(route('admin.meeting-packs.publish', $plan));

        $response->assertRedirect(route('admin.meeting-packs.show', $plan));
        $this->assertSame('published', $plan->fresh()->status->value);
        $this->assertDatabaseHas('meeting_packs',[
            'status' => 'published',
        ]);
    }

    public function test_cannot_publish_archived(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->archived()->create();

        $response = $this->actingAs($admin)->postJson(route('admin.meeting-packs.publish', $plan));

        $this->assertDatabaseMissing('meeting_packs',[
            'status' => 'published',
        ]);
    }

    public function test_coach_cannot_publish(): void
    {
        $coach = User::factory()->coach()->create();
        $plan = MeetingPack::factory()->draft()->create();

        $response = $this->actingAs($coach)->post(route('admin.meeting-packs.publish', $plan));

        $response->assertForbidden();
    }

    public function test_student_cannot_publish(): void
    {
        $student = User::factory()->student()->create();
        $plan = MeetingPack::factory()->draft()->create();

        $response = $this->actingAs($student)->post(route('admin.meeting-packs.publish', $plan));

        $response->assertForbidden();
    }
}
