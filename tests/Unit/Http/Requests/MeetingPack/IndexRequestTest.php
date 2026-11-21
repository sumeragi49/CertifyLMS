<?php

namespace Tests\Unit\Http\Requests\MeetingPack;

use App\Enums\MeetingPackStatus;
use App\Models\User;
use App\Models\MeetingPack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IndexRequestTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;

    public function test_validation_passes_with_empty_filters(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.meeting-packs.index'));

        $response->assertSuccessful();
    }

    public function test_validation_passes_with_all_filters_set(): void
    {
        $admin = User::factory()->admin()->create();
        $plan = MeetingPack::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.meeting-packs.index', [
            'keyword' => '1回パック',
            'status' => MeetingPackStatus::Published->value,
        ]));

        $response->assertSuccessful();
    }

    public function test_student_cannot_access(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('admin.meeting-packs.index'));

        $response->assertForbidden();
    }

    public function test_coach_cannot_access(): void
    {
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->get(route('admin.meeting-packs.index'));

        $response->assertForbidden();
    }

    #[DataProvider('invalidFilterPayloads')]
    public function test_validation_fails(array $params, string $expectedErrorField): void
    {
        // Arrange
        $admin = User::factory()->admin()->create();

        // Act
        $response = $this->actingAs($admin)->getJson(route('admin.meeting-packs.index', $params));

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrorField);
    }

    public static function invalidFilterPayloads(): array
    {
        return [
            'keyword 101 文字で 422' => [['keyword' => str_repeat('a', 101)], 'keyword'],
            'status 不正値で 422' => [['status' => 'unknown'], 'status'],
        ];
    }
}
