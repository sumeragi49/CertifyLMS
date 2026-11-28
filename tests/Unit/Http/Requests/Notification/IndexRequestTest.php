<?php

namespace Tests\Unit\Http\Requests\Notification;

use App\Models\User;
use App\Models\InfoNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IndexRequestTest extends TestCase
{
    public function test_validation_student_passes_with_all_notifications(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('notifications.index'));

        $response->assertSuccessful();
    }

    public function test_validation_coach_passes_with_all_notifications(): void
    {
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->get(route('notifications.index'));

        $response->assertSuccessful();
    }

    public function test_admin_cannot_access(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('notifications.index'));

        $response->assertForbidden();
    }

    #[DataProvider('invalidFilterPayloads')]
    public function test_validation_fails(array $params, string $expectedErrorField): void
    {
        // Arrange
        $student = User::factory()->student()->create();

        // Act
        $response = $this->actingAs($student)->getJson(route('notifications.index', $params));

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrorField);
    }

    public static function invalidFilterPayloads(): array
    {
        return [
            'tab unknown で 422' => [['tab' => 'unknown'], 'tab'],
            'page 0 で 422' => [['page' => 0], 'page'],
            'page 文字列で 422' => [['page' => 'abc'], 'page'],
        ];
    }
}
