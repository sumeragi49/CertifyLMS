<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\QaReply;
use App\Policies\ReplyPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReplyPolicyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;

    public function test_author_student_full_access(): void
    {
        $student = User::factory()->student()->create();
        $qaReply = QaReply::factory()->create([
            'user_id' => $student->id,
        ]);
        $policy = new ReplyPolicy;

        $this->assertTrue($policy->view($student, $qaReply));
        $this->assertTrue($policy->create($student, $qaReply));
        $this->assertTrue($policy->update($student, $qaReply));
        $this->assertTrue($policy->delete($student, $qaReply));
    }

    public function test_coach_full_access(): void
    {
        $coach = User::factory()->coach()->create();
        $qaReply = QaReply::factory()->create([
            'user_id' => $coach->id,
        ]);
        $policy = new ReplyPolicy;

        $this->assertTrue($policy->view($coach, $qaReply));
        $this->assertTrue($policy->create($coach, $qaReply));
        $this->assertTrue($policy->update($coach, $qaReply));
        $this->assertTrue($policy->delete($coach, $qaReply));
    }

    public function test_admin_full_access(): void
    {
        $admin = User::factory()->admin()->create();
        $qaReply = QaReply::factory()->create();
        $policy = new ReplyPolicy;

        $this->assertTrue($policy->delete($admin, $qaReply));
    }
}
