<?php

declare(strict_types=1);

namespace Tests\Feature\Http\MeetingPack;

use App\Models\MeetingPack;
use App\Models\User;
use App\Exceptions\MeetingPack\MeetingPackNotDeletableException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_draft_meeting_pack(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->draft()->create();

        $response = $this->actingAs($admin)->delete(route('admin.meeting-packs.destroy', $plan));

        $response->assertRedirect(route('admin.meeting-packs.index'));
        $this->assertDatabaseMissing('meeting_packs', ['id' => $plan->id]);
    }

    public function test_cannot_delete_published_meeting_pack(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->published()->create();

        $response = $this->actingAs($admin)->delete(route('admin.meeting-packs.destroy', $plan));

        $this->assertDatabaseHas('meeting_packs', [
            'id' => $plan->id,
        ]);
    }

    public function test_admin_can_delete_archive_meeting_pack(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->archived()->create();

        $response = $this->actingAs($admin)->delete(route('admin.meeting-packs.destroy', $plan));

        $response->assertRedirect(route('admin.meeting-packs.index'));
        $this->assertDatabaseMissing('meeting_packs', ['id' => $plan->id]);
    }
}
