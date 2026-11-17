<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_view_qa_thread_edit(): void
    {
        $qaThread = QaThread::factory()->createCertificate()->create();
        $student = $qaThread->user;

        $response = $this->actingAs($student)->get(route('qa-board.edit', ['thread' => $qaThread]));

        $response->assertOk();
        $response->assertViewIs('qa-thread.edit');
    }
}
