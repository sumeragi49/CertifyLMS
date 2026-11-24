<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Plan;

use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlanLog;
use App\Exceptions\Plan\PlanNotDeletableException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_draft_plan(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->draft()->create();

        $response = $this->actingAs($admin)->delete(route('admin.plans.destroy', $plan));

        $response->assertRedirect(route('admin.plans.index'));
        $this->assertDatabaseMissing('plans', ['id' => $plan->id]);
    }

    public function test_cannot_delete_published_plan(): void
    {
        //$this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->published()->create();

        $response = $this->actingAs($admin)->delete(route('admin.plans.destroy', $plan));

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
        ]);
    }

    public function test_admin_can_delete_archive_plan(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->archived()->create();

        $response = $this->actingAs($admin)->delete(route('admin.plans.destroy', $plan));

        $response->assertRedirect(route('admin.plans.index'));
        $this->assertDatabaseHas('plans', [
            'id' => $plan->id
        ]);
    }

    public function test_admin_can_delete_draft_binding_user_plan(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->draft()->create();
        $userPlanLog = UserPlanLog::factory()->create([
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.plans.destroy', $plan));

        $response->assertRedirect(route('admin.plans.index'));
        $this->assertDatabaseHas('plans', ['id' => $plan->id]);
    }
}
