<?php

declare(strict_types=1);

namespace Tests\Feature\Http\MeetingPack;

use App\Models\MeetingPack;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_meeting_packs_list(): void
    {
        $admin = User::factory()->admin()->create();
        MeetingPack::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.meeting-packs.index'));

        $response->assertOk();
        $response->assertViewIs('meeting-pack.management.index');
    }

    public function test_coach_cannot_access_admin_meeting_packs_index(): void
    {
        $coach = User::factory()->coach()->create();

        $this->actingAs($coach)
            ->get(route('admin.meeting-packs.index'))
            ->assertForbidden();
    }

    public function test_student_cannot_access_admin_meeting_packs_index(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)
            ->get(route('admin.meeting-packs.index'))
            ->assertForbidden();
    }

    public function test_keyword_filter_matches_name_only(): void
    {
        $admin = User::factory()->admin()->create();
        MeetingPack::factory()->published()->create(['name' => '12回パック']);
        MeetingPack::factory()->published()->create(['name' => '15回パック']);

        $response = $this->actingAs($admin)->get(route('admin.meeting-packs.index', ['keyword' => '12回']));

        $response->assertOk();
        $response->assertSee('12回パック');
        $response->assertDontSee('15回パック');
    }

    public function test_status_filter_returns_only_matching_status(): void
    {
        $admin = User::factory()->admin()->create();
        $plan1 = MeetingPack::factory()->create([
            'name' => '12回パック',
            'status' => 'draft']);
        $plan2 = MeetingPack::factory()->create([
            'name' => '15回パック',
            'status' => 'published']);
        $plan3 = MeetingPack::factory()->create([
            'name' => '18回パック',
            'status' => 'archived']);

        $response = $this->actingAs($admin)->get(route('admin.meeting-packs.index', ['status' => 'published']));

        $response->assertOk();
        $response->assertSee($plan2->name);
        $response->assertDontSee($plan1->name);
        $response->assertDontSee($plan3->name);
    }

    public function test_paginates_10_per_page(): void
    {
        $admin = User::factory()->admin()->create();
        MeetingPack::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.meeting-packs.index'));

        $response->assertOk();
        $plans = $response->viewData('plans');
        $this->assertSame(10, $plans->perPage());
        $this->assertSame(1, $plans->total());
    }
}
