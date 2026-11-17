<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_update_qa_thread(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = $qaThread->user;

        $qaUpdateThread = [
            'title' => '模擬試験に関して(編集済み)',
            'body' => '模擬試験を受講する日を変更したいです(編集済み)',
        ];

        $response = $this->actingAs($student)->patch(route('qa-board.update', $qaThread), $qaUpdateThread);

        $qaUpdatedThread = QaThread::latest('updated_at')->first();

        $response->assertRedirect(route('qa-board.show', ['thread' => $qaUpdatedThread]));

        $this->assertDatabaseHas('qa_threads', [
            'title' => $qaUpdatedThread->title,
            'body' => $qaUpdatedThread->body,
        ]);
    }

    public function test_other_student_cannot_update_qa_thread(): void
    {
        $student = User::factory()->student()->create();
        $qaThread = QaThread::factory()->createCertificate()->create();

        $qaUpdateThread = [
            'title' => '模擬試験に関して(編集済み)',
            'body' => '模擬試験を受講する日を変更したいです(編集済み)',
        ];

        $response = $this->actingAs($student)->patch(route('qa-board.update', $qaThread), $qaUpdateThread);

        $response->assertStatus(403);
    }

    public function test_coach_cannot_update_qa_thread(): void
    {
        $coach = User::factory()->coach()->create();
        $qaThread = QaThread::factory()->createCertificate()->create();

        $qaUpdateThread = [
            'title' => '模擬試験に関して(編集済み)',
            'body' => '模擬試験を受講する日を変更したいです(編集済み)',
        ];

        $response = $this->actingAs($coach)->patch(route('qa-board.update', $qaThread), $qaUpdateThread);

        $response->assertStatus(403);
    }

    public function test_admin_cannot_update_qa_thread(): void
    {
        $admin = User::factory()->admin()->create();
        $qaThread = QaThread::factory()->createCertificate()->create();

        $qaUpdateThread = [
            'title' => '模擬試験に関して(編集済み)',
            'body' => '模擬試験を受講する日を変更したいです(編集済み)',
        ];

        $response = $this->actingAs($admin)->patch(route('qa-board.update', $qaThread), $qaUpdateThread);

        $response->assertStatus(403);
    }

    public function test_student_cannot_update_qa_thread_title_required(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = $qaThread->user;

        $qaUpdateThread = [
            'title' => '',
            'body' => '模擬試験を受講する日を変更したいです(編集済み)',
        ];

        $response = $this->actingAs($student)->patch(route('qa-board.update', $qaThread), $qaUpdateThread);

        $response->assertSessionHasErrors('title');
    }

    public function test_student_cannot_update_qa_thread_body_required(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = $qaThread->user;

        $qaUpdateThread = [
            'title' => '模擬試験に関して(編集済み)',
            'body' => '',
        ];

        $response = $this->actingAs($student)->patch(route('qa-board.update', $qaThread), $qaUpdateThread);

        $response->assertSessionHasErrors('body');
    }
}
