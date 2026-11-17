<?php

namespace Tests\Feature\Http\QaReply;

use App\Models\User;
use App\Models\QaThread;
use App\Models\QaReply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_view_qa_reply_edit(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $student = $qaReply->user;

        $response = $this->actingAs($student)->get(route('qa-board.replies.edit',['thread'=>$qaThread,'reply'=>$qaReply]));

        $response->assertOk();
        $response->assertViewIs('qa-thread.reply-edit');
    }

    public function test_other_student_cannot_view_qa_reply_edit(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('qa-board.replies.edit',['thread'=>$qaThread,'reply'=>$qaReply]));

        $response->assertStatus(403);
    }

    public function test_coach_cannot_view_qa_reply_edit(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->get(route('qa-board.replies.edit',['thread'=>$qaThread,'reply'=>$qaReply]));

        $response->assertStatus(403);
    }

    public function test_admin_cannot_view_qa_reply_edit(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('qa-board.replies.edit',['thread'=>$qaThread,'reply'=>$qaReply]));

        $response->assertStatus(403);
    }
}
