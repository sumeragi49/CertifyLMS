<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResolveTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_update_qa_thread_status_resolved(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = $qaThread->user;

        $response = $this->actingAs($student)->post(route('qa-board.resolve', $qaThread));

        $qaUpdatedThread = QaThread::latest('updated_at')->first();

        $response->assertRedirect(route('qa-board.show', ['thread' => $qaUpdatedThread]));

        $this->assertDatabaseHas('qa_threads', [
            'status' => 'resolved',
        ]);
    }

    public function test_other_user_cannot_update_qa_thread_status_resolved(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->post(route('qa-board.resolve', $qaThread));

        $response->assertStatus(403);
    }

    public function test_coach_cannot_update_qa_thread_status_resolved(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->post(route('qa-board.resolve', $qaThread));

        $response->assertStatus(403);
    }

    public function test_admin_cannot_update_qa_thread_status_resolved(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('qa-board.resolve', $qaThread));

        $response->assertStatus(403);
    }
}
