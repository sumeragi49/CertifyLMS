<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_delete_qa_thread(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = $qaThread->user;

        $response = $this->actingAs($student)->delete(route('qa-board.destroy', $qaThread));

        $response->assertRedirect(route('qa-board.index'));

        $this->assertDatabaseMissing('qa_threads', [
            'title' => $qaThread->title,
            'body' => $qaThread->body,
        ]);
    }

    public function test_other_student_cannot_delete_qa_thread(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->delete(route('qa-board.destroy', $qaThread));

        $response->assertStatus(403);
    }

    public function test_coach_cannot_delete_qa_thread(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->delete(route('qa-board.destroy', $qaThread));

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_qa_thread(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete(route('admin.qa-board.destroy', $qaThread));

        $response->assertRedirect(route('admin.qa-board.index'));

        $this->assertDatabaseMissing('qa_threads', [
            'title' => $qaThread->title,
            'body' => $qaThread->body,
        ]);
    }
}
