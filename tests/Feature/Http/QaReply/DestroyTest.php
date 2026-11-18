<?php

namespace Tests\Feature\Http\QaReply;

use App\Models\User;
use App\Models\QaThread;
use App\Models\QaReply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_delete_qa_reply(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $student = $qaReply->user;

        $response = $this->actingAs($student)->delete(route('qa-board.replies.destroy',['thread'=>$qaThread,'reply'=>$qaReply]));

        $response->assertRedirect(route('qa-board.show', ['thread' => $qaThread]));

        $this->assertDatabaseMissing('qa_replies', [
            'user_id' => $student->id,
            'qa_thread_id' => $qaThread->id,
            'body' => $qaReply->body,
        ]);
    }

    public function test_other_student_cannot_delete_qa_reply(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->delete(route('qa-board.replies.destroy',['thread'=>$qaThread,'reply'=>$qaReply]));

        $response->assertStatus(403);
    }

    public function test_coach_cannot_delete_qa_reply(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->delete(route('qa-board.replies.destroy',['thread'=>$qaThread,'reply'=>$qaReply]));

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_qa_reply(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $student = $qaReply->user;
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete(route('admin.qa-board.replies.destroy',['thread'=>$qaThread,'reply'=>$qaReply]));

        $response->assertRedirect(route('admin.qa-board.show', ['thread' => $qaThread]));

        $this->assertDatabaseMissing('qa_replies', [
            'user_id' => $student->id,
            'qa_thread_id' => $qaThread->id,
            'body' => $qaReply->body,
        ]);
    }
}
