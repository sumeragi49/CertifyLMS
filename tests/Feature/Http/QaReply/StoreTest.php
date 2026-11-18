<?php

namespace Tests\Feature\Http\QaReply;

use App\Models\User;
use App\Models\QaThread;
use App\Models\QaReply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_store_qa_reply(): void
    {
        $student = User::factory()->student()->create();

        $qaThread = QaThread::factory()->createCertificate()->create();

        $qaReply = [
            'user_id' => $student->id,
            'qa_thread_id' => $qaThread->id,
            'body' => '模試について、サポートセンターについて聞いた方がいいです。'
        ];

        $response = $this->actingAs($student)->post(route('qa-board.replies.store',$qaThread),$qaReply);

        $response->assertRedirect(route('qa-board.show', ['thread' => $qaThread]));

        $this->assertDatabaseHas('qa_replies', [
            'user_id' => $student->id,
            'qa_thread_id' => $qaThread->id,
            'body' => '模試について、サポートセンターについて聞いた方がいいです。',
        ]);
    }

    public function test_coach_can_store_qa_reply(): void
    {
        $coach = User::factory()->coach()->create();

        $qaThread = QaThread::factory()->createCertificate()->create();

        $qaReply = [
            'user_id' => $coach->id,
            'qa_thread_id' => $qaThread->id,
            'body' => '模試について、サポートセンターについて聞いた方がいいです。'
        ];

        $response = $this->actingAs($coach)->post(route('qa-board.replies.store',$qaThread),$qaReply);

        $response->assertRedirect(route('qa-board.show', ['thread' => $qaThread]));

        $this->assertDatabaseHas('qa_replies', [
            'user_id' => $coach->id,
            'qa_thread_id' => $qaThread->id,
            'body' => '模試について、サポートセンターについて聞いた方がいいです。',
        ]);
    }

    public function test_admin_cannot_store_qa_reply(): void
    {
        $admin = User::factory()->admin()->create();

        $qaThread = QaThread::factory()->createCertificate()->create();

        $qaReply = [
            'user_id' => $admin->id,
            'qa_thread_id' => $qaThread->id,
            'body' => '模試について、サポートセンターについて聞いた方がいいです。'
        ];

        $response = $this->actingAs($admin)->post(route('qa-board.replies.store',$qaThread),$qaReply);

        $response->assertStatus(403);
    }

    public function test_student_cannot_store_qa_reply_body_required(): void
    {
        $student = User::factory()->student()->create();

        $qaThread = QaThread::factory()->createCertificate()->create();

        $qaReply = [
            'user_id' => $student->id,
            'qa_thread_id' => $qaThread->id,
            'body' => ''
        ];

        $response = $this->actingAs($student)->post(route('qa-board.replies.store',$qaThread),$qaReply);

        $response->assertSessionHasErrors('body');
    }
}
