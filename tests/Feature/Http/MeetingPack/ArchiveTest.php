<?php

declare(strict_types=1);

namespace Tests\Feature\Http\MeetingPack;

use App\Models\MeetingPack;
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
        $plan = MeetingPack::factory()->published()->create();

        $response = $this->actingAs($admin)->post(route('admin.meeting-packs.archive', $plan));

        $response->assertRedirect(route('admin.meeting-packs.show', $plan));
        $this->assertSame('archived', $plan->fresh()->status->value);
        $this->assertDatabaseHas('meeting_packs',[
            'status' => 'archived',
        ]);
    }

    public function test_cannot_draft_archived(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->draft()->create();

        $response = $this->actingAs($admin)->post(route('admin.meeting-packs.archive', $plan));

        $this->assertDatabaseMissing('meeting_packs',[
            'status' => 'archived',
        ]);
    }

    public function test_coach_cannot_archive(): void
    {
        $coach = User::factory()->coach()->create();
        $plan = MeetingPack::factory()->draft()->create();

        $response = $this->actingAs($coach)->post(route('admin.meeting-packs.archive', $plan));

        $response->assertForbidden();
    }

    public function test_student_cannot_archive(): void
    {
        $student = User::factory()->student()->create();
        $plan = MeetingPack::factory()->draft()->create();

        $response = $this->actingAs($student)->post(route('admin.meeting-packs.archive', $plan));

        $response->assertForbidden();
    }
}
