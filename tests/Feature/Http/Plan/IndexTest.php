<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Plan;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_plans_list(): void
    {
        $admin = User::factory()->admin()->create();
        Plan::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.plans.index'));

        $response->assertOk();
        $response->assertViewIs('plan.management.index');
    }

    public function test_coach_cannot_access_admin_plans_index(): void
    {
        $coach = User::factory()->coach()->create();

        $this->actingAs($coach)
            ->get(route('admin.plans.index'))
            ->assertForbidden();
    }

    public function test_student_cannot_access_admin_plans_index(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)
            ->get(route('admin.plans.index'))
            ->assertForbidden();
    }

    public function test_keyword_filter_matches_name_only(): void
    {
        $admin = User::factory()->admin()->create();
        plan::factory()->published()->create(['name' => '4ヵ月プラン']);
        plan::factory()->published()->create(['name' => '9ヵ月プラン']);

        $response = $this->actingAs($admin)->get(route('admin.plans.index', ['keyword' => '4ヵ月']));

        $response->assertOk();
        $response->assertSee('4ヵ月プラン');
        $response->assertDontSee('9ヵ月プラン');
    }

    public function test_status_filter_returns_only_matching_status(): void
    {
        $admin = User::factory()->admin()->create();
        $plan1 = Plan::factory()->create([
            'name' => '4ヵ月プラン',
            'status' => 'draft']);
        $plan2 = Plan::factory()->create([
            'name' => '9ヵ月プラン',
            'status' => 'published']);
        $plan3 = Plan::factory()->create([
            'name' => '12ヵ月プラン',
            'status' => 'archived']);

        $response = $this->actingAs($admin)->get(route('admin.plans.index', ['status' => 'published']));

        $response->assertOk();
        $response->assertSee($plan2->name);
        $response->assertDontSee($plan1->name);
        $response->assertDontSee($plan3->name);
    }

    public function test_paginates_10_per_page(): void
    {
        $admin = User::factory()->admin()->create();
        Plan::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.plans.index'));

        $response->assertOk();
        $plans = $response->viewData('plans');
        $this->assertSame(10, $plans->perPage());
        $this->assertSame(1, $plans->total());
    }
}
