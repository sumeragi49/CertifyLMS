<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Requests\Plan;

use App\Enums\PlanStatus;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_passes_with_valid_plan(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->create();

        $updatePlan = [
            'name' => '3ヶ月プラン12回',
            'description' => '12回の面談プランです。',
            'duration_days' => '90',
            'default_meeting_quota' => 12,
            'status' => 'draft',
            'sort_order' => 12,
            'created_by_user_id' => $admin->id,
            'updated_by_user_id' => $admin->id,
        ];

        $response = $this->actingAs($admin)->put(route('admin.plans.update', $plan), $updatePlan);

        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('plans', ['name' => '3ヶ月プラン12回']);
    }

    #[DataProvider('invalidPayloads')]
    public function test_validation_fails(array $overrides, string $expectedErrorField): void
    {
        $admin = User::factory()->admin()->create();
        $plan = Plan::factory()->create();

        $updatePlan = array_merge([
            'name' => 'Sample Cert',
        ], $overrides);

        $response = $this->actingAs($admin)->putJson(route('admin.plans.update', $plan), $updatePlan);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrorField);
    }

    public function test_authorize_returns_false_for_coach(): void
    {
        $coach = User::factory()->coach()->create();
        $plan = Plan::factory()->create();

        $response = $this->actingAs($coach)->putJson(route('admin.plans.update',$plan), [
            'name' => 'Sample',
        ]);

        $response->assertForbidden();
    }

    public function test_authorize_returns_false_for_student(): void
    {
        $student = User::factory()->student()->create();
        $plan = Plan::factory()->create();

        $response = $this->actingAs($student)->putJson(route('admin.plans.update', $plan), [
            'name' => 'Sample',
        ]);

        $response->assertForbidden();
    }

    public static function invalidPayloads(): array
    {
        return [
            'name 未指定で 422' => [['name' => ''], 'name'],
            'name 101 文字で 422' => [['name' => str_repeat('a', 101)], 'name'],
            'description 2001 文字で 422' => [['description' => str_repeat('b', 2001)], 'description'],
            'duration_days 未指定で 422' => [['duration_days' => ''], 'duration_days'],
            'duration_days 0で422' => [['duration_days' => 0], 'duration_days'],
            'duration_days 3651で422' => [['duration_days' => 3651], 'duration_days'],
            'default_meeting_quota 未指定で 422' => [['default_meeting_quota' => ''], 'default_meeting_quota'],
            'default_meeting_quota -1で 422' => [['default_meeting_quota' => -1], 'default_meeting_quota'],
            'default_meeting_quota 1001で 422' => [['default_meeting_quota' => 1001], 'default_meeting_quota'],
            'sort_order 未指定で422' => [['sort_order' => ''], 'sort_order'],
            'sort_order -1で422' => [['sort_order' => -1], 'sort_order'],
        ];
    }
}
