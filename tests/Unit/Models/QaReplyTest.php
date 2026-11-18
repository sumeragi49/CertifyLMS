<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\QaThread;
use App\Models\QaReply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QaReplyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;

    public function test_user_relation_returns_owner_user(): void
    {
        $user = User::factory()->student()->create();
        $qaReply = QaReply::factory()->for($user)->create();

        $owner = $qaReply->user;

        $this->assertTrue($owner->is($user));
    }

    public function test_thread_relation_returns_thread(): void
    {
        $qaThread = QaThread::factory()->create();
        $qaReply = QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);

        $thread = $qaReply->thread;

        $this->assertTrue($thread->is($qaThread));
    }
}
