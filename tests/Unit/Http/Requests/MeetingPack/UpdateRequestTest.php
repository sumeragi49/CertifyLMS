<?php

namespace Tests\Unit\Http\Requests\MeetingPack;

use App\Enums\MeetingPackStatus;
use App\Models\User;
use App\Models\MeetingPack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_validation_passes_with_valid_meeting_pack(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->create();

        $updatePlan = [
            'name' => '12回パック',
            'description' => 'ちょうど3か月分の面談パックです。',
            'meeting_count' => '12',
            'price' => 150000,
            'stripe_price_id' => null,
            'status' => 'draft',
            'sort_order' => 15,
            'updated_by_user_id' => $admin->id,
        ];

        $response = $this->actingAs($admin)->patch(route('admin.meeting-packs.update', $plan), $updatePlan);

        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('meeting_packs', ['name' => '12回パック']);
    }

    #[DataProvider('invalidPayloads')]
    public function test_validation_fails(array $overrides, string $expectedErrorField): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->create();

        $updatePlan = array_merge([
            'name' => 'Sample Cert',
        ], $overrides);

        $response = $this->actingAs($admin)->patchJson(route('admin.meeting-packs.update', $plan), $updatePlan);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrorField);
    }

    public function test_authorize_returns_false_for_coach(): void
    {
        $coach = User::factory()->coach()->create();
        $plan = MeetingPack::factory()->create();

        $response = $this->actingAs($coach)->patchJson(route('admin.meeting-packs.update',$plan), [
            'name' => 'Sample',
        ]);

        $response->assertForbidden();
    }

    public function test_authorize_returns_false_for_student(): void
    {
        $student = User::factory()->student()->create();
        $plan = MeetingPack::factory()->create();

        $response = $this->actingAs($student)->patchJson(route('admin.meeting-packs.update', $plan), [
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
            'meeting_count 未指定で 422' => [['meeting_count' => ''], 'meeting_count'],
            'meeting_count 0で422' => [['meeting_count' => 0], 'meeting_count'],
            'meeting_count 101で422' => [['meeting_count' => 101], 'meeting_count'],
            'price 未指定で 422' => [['price' => ''], 'price'],
            'price 1000001で422' => [['price' => 1000001], 'price'],
            'stripe_price_id 256文字で422' => [['stripe_price_id' => str_repeat('a', 256)], 'stripe_price_id'],
            'sort_order 未指定で422' => [['sort_order' => ''], 'sort_order'],
            'sort_order -1で422' => [['sort_order' => -1], 'sort_order'],
        ];
    }
}
