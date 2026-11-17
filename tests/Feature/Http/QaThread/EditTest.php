<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_view_qa_thread_edit(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = $qaThread->user;

        $response = $this->actingAs($student)->get(route('qa-board.edit', ['thread' => $qaThread]));

        $response->assertOk();
        $response->assertViewIs('qa-thread.edit');
    }

    public function test_other_student_cannot_view_qa_thread_edit(): void
    {
        $student = User::factory()->student()->create();
        $qaThread = QaThread::factory()->createCertificate()->create();

        $response = $this->actingAs($student)->get(route('qa-board.edit', ['thread' => $qaThread]));

        $response->assertStatus(403);
    }

    public function test_coach_cannot_view_qa_thread_edit(): void
    {
        $coach = User::factory()->coach()->create();
        $qaThread = QaThread::factory()->createCertificate()->create();

        $response = $this->actingAs($coach)->get(route('qa-board.edit', ['thread' => $qaThread]));

        $response->assertStatus(403);
    }

    public function test_admin_cannot_view_qa_thread_edit(): void
    {
        $admin = User::factory()->admin()->create();
        $qaThread = QaThread::factory()->createCertificate()->create();

        $response = $this->actingAs($admin)->get(route('qa-board.edit', ['thread' => $qaThread]));

        $response->assertStatus(403);
    }
}
