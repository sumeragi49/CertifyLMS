<?php

namespace Tests\Feature\Http\QaReply;

use App\Models\User;
use App\Models\QaThread;
use App\Models\QaReply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_qa_reply_update(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $student = $qaReply->user;

        $qaUpdateReply = [
            'body' => '模試の範囲を教えてください(修正済)',
        ];

        $response = $this->actingAs($student)->patch(route('qa-board.replies.update',['thread'=>$qaThread,'reply'=>$qaReply]),$qaUpdateReply );

        $response->assertRedirect(route('qa-board.show', ['thread' => $qaThread]));

        $this->assertDatabaseHas('qa_replies', [
            'body' => '模試の範囲を教えてください(修正済)',
        ]);
    }

    public function test_other_student_cannot_qa_reply_update(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $student = User::factory()->student()->create();

        $qaUpdateReply = [
            'body' => '模試の範囲を教えてください(修正済)',
        ];

        $response = $this->actingAs($student)->patch(route('qa-board.replies.update',['thread'=>$qaThread,'reply'=>$qaReply]),$qaUpdateReply );

        $response->assertStatus(403);
    }

    public function test_coach_cannot_qa_reply_update(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $coach = User::factory()->coach()->create();

        $qaUpdateReply = [
            'body' => '模試の範囲を教えてください(修正済)',
        ];

        $response = $this->actingAs($coach)->patch(route('qa-board.replies.update',['thread'=>$qaThread,'reply'=>$qaReply]),$qaUpdateReply );

        $response->assertStatus(403);
    }

    public function test_admin_cannot_qa_reply_update(): void
    {
        $qaThread =QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        $admin = User::factory()->admin()->create();

        $qaUpdateReply = [
            'body' => '模試の範囲を教えてください(修正済)',
        ];

        $response = $this->actingAs($admin)->patch(route('qa-board.replies.update',['thread'=>$qaThread,'reply'=>$qaReply]),$qaUpdateReply );

        $response->assertStatus(403);
    }
}
