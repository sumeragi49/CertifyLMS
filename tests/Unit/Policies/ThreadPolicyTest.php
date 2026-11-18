<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\QaThread;
use App\Policies\ThreadPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ThreadPolicyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;

    public function test_author_student_full_access(): void
    {
        $student = User::factory()->student()->create();
        $qaThread = QaThread::factory()->create([
            'user_id' => $student->id,
        ]);
        $policy = new ThreadPolicy;

        $this->assertTrue($policy->viewAny($student, $qaThread));
        $this->assertTrue($policy->view($student, $qaThread));
        $this->assertTrue($policy->create($student, $qaThread));
        $this->assertTrue($policy->update($student, $qaThread));
        $this->assertTrue($policy->delete($student, $qaThread));
        $this->assertTrue($policy->resolve($student, $qaThread));
        $this->assertTrue($policy->unResolve($student, $qaThread));
    }

    public function test_coach_full_access(): void
    {
        $coach = User::factory()->coach()->create();
        $qaThread = QaThread::factory()->create();
        $policy = new ThreadPolicy;

        $this->assertTrue($policy->viewAny($coach, $qaThread));
        $this->assertTrue($policy->view($coach, $qaThread));
    }

    public function test_admin_full_access(): void
    {
        $admin = User::factory()->admin()->create();
        $qaThread = QaThread::factory()->create();
        $policy = new ThreadPolicy;

        $this->assertTrue($policy->viewAny($admin, $qaThread));
        $this->assertTrue($policy->view($admin, $qaThread));
        $this->assertTrue($policy->delete($admin, $qaThread));
    }
}
