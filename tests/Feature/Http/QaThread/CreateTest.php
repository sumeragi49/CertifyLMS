<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_view_qa_thread_create(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('qa-board.create'));

        $response->assertOk();
        $response->assertViewIs('qa-thread.create');
    }

    public function test_coach_cannot_view_qa_thread_create(): void
    {
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->get(route('qa-board.create'));

        $response->assertStatus(403);
        $response->assertSee('403');
    }

    public function test_admin_cannot_view_qa_thread_create(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('qa-board.create'));

        $response->assertStatus(403);
        $response->assertSee('403');
    }
}
