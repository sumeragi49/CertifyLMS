<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_view_qa_thread_show(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = $qaThread->user;

        $response = $this->actingAs($student)->get(route('qa-board.show', ['thread' => $qaThread]));

        $response->assertOk();
        $response->assertViewIs('qa-thread.show');
    }

    public function test_coach_can_view_qa_thread_show(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();

        $coach = User::factory()->coach()->create();

        $response = $this->actingAs($coach)->get(route('qa-board.show', ['thread' => $qaThread]));

        $response->assertOk();
        $response->assertViewIs('qa-thread.show');
    }

    public function test_admin_cannot_view_qa_thread_show(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.qa-board.show', ['thread' => $qaThread]));

        $response->assertOk();
        $response->assertViewIs('qa-thread.show');
    }
}
