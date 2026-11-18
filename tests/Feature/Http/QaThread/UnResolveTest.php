<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnResolveTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_update_qa_thread_status_unresolved(): void
    {
        $qaThread = QaThread::factory()->resolved()->createCertificate()->create();
        $student = $qaThread->user;

        $response = $this->actingAs($student)->post(route('qa-board.unresolve', $qaThread));

        $qaUpdatedThread = QaThread::latest('updated_at')->first();

        $response->assertRedirect(route('qa-board.show', ['thread' => $qaUpdatedThread]));

        $this->assertDatabaseHas('qa_threads', [
            'status' => 'unresolved',
        ]);
    }

    public function test_other_student_cannot_update_qa_thread_status_unresolved(): void
    {
        $qaThread = QaThread::factory()->resolved()->createCertificate()->create();
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->post(route('qa-board.unresolve', $qaThread));

        $response->assertStatus(403);
    }

    public function test_coach_cannot_update_qa_thread_status_unresolved(): void
    {
        $qaThread = QaThread::factory()->resolved()->createCertificate()->create();
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->post(route('qa-board.unresolve', $qaThread));

        $response->assertStatus(403);
    }

    public function test_admin_cannot_update_qa_thread_status_unresolved(): void
    {
        $qaThread = QaThread::factory()->resolved()->createCertificate()->create();
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('qa-board.unresolve', $qaThread));

        $response->assertStatus(403);
    }
}
