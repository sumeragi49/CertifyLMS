<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\QaThread;
use App\Models\QaReply;
use App\Models\Certification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QaThreadTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    use RefreshDatabase;

    public function test_user_relation_returns_owner_user(): void
    {
        $user = User::factory()->student()->create();
        $qaThread = QaThread::factory()->for($user)->create();

        $owner = $qaThread->user;

        $this->assertTrue($owner->is($user));
    }

    public function test_certification_relation_returns_owner_user(): void
    {
        $certification = Certification::factory()->create();
        $qaThread = QaThread::factory()->for($certification)->create();

        $owner = $qaThread->certification;

        $this->assertTrue($owner->is($certification));
    }

    public function test_replies_relation_returns_attached_parts(): void
    {
        $qaThread = QaThread::factory()->create();
        QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);
        QaReply::factory()->create([
            'qa_thread_id' => $qaThread->id,
        ]);

        $qaReplies = $qaThread->replies;

        $this->assertCount(2, $qaReplies, '対象 qaThread の reply のみが取得されるはず');
    }

    public function test_scope_resolved_filters_only_unresolved(): void
    {
        $resolvedThread = QaThread::factory()->create();

        $results = QaThread::where('status', 'unresolved')
                 ->get();

        $this->assertCount(1, $results, 'status=unresolved qaThread のみ抽出されるはず');
        $this->assertTrue($results->first()->is($resolvedThread));
    }

    public function test_scope_keyword_filters_by_body_match(): void
    {
        $target = QaThread::factory()->create([
            'body' => '模擬試験',
        ]);
        QaThread::factory()->create([
            'body' => '本番',
        ]);

        $results = QaThread::keyword('模擬試験')->get();

        $this->assertCount(1, $results, 'キーワード VPC を含む section のみが抽出されるはず');
        $this->assertTrue($results->first()->is($target));
    }
}
