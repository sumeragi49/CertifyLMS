<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use App\Models\Certification;
use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_student_can_view_qa_thread(): void
    {
        $student = User::factory()->student()->create();
        QaThread::factory()->count(3)->create();

        $response = $this->actingAs($student)->get(route('qa-board.index'));

        $response->assertOk();
        $response->assertViewIs('qa-thread.index');
    }

    public function test_coach_can_view_qa_thread(): void
    {
        $coach = User::factory()->coach()->create();
        QaThread::factory()->count(3)->create();

        $response = $this->actingAs($coach)->get(route('qa-board.index'));

        $response->assertOk();
        $response->assertViewIs('qa-thread.index');
    }

    public function test_admin_can_view_qa_thread(): void
    {
        $admin = User::factory()->admin()->create();
        QaThread::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.qa-board.index'));

        $response->assertOk();
        $response->assertViewIs('qa-thread.index');
    }

    public function test_keyword_filter_matches_body_only(): void
    {
        $qaThread1 = QaThread::factory()->createCertificate()->create([
            'body' => '試験本番について'
        ]);
        $qaThread2 = QaThread::factory()->createCertificate()->create([
            'body' => '教材の不備について'
        ]);

        $student = $qaThread1->user;

        $response = $this->actingAs($student)->get(route('qa-board.index', ['keyword' => '本番']));

        $response->assertOk();

        $this->assertDatabaseHas('qa_threads', [
            'id' => $qaThread1->id,
            'body' => '試験本番について',
        ]);

        $response->assertSee('試験本番について');
        $response->assertDontSee('教材の不備について');
    }

    public function test_status_filter_matches_unresolved_only(): void
    {
        $qaThread1 = QaThread::factory()->createCertificate()->create([
            'title' => '試験本番について',
            'status' => 'unresolved',
        ]);
        $qaThread2 = QaThread::factory()->createCertificate()->create([
            'title'=> '教材の不備について',
            'status' => 'resolved',
        ]);

        $student = $qaThread1->user;

        $response = $this->actingAs($student)->get(route('qa-board.index', ['status' => 'unresolved']));

        $response->assertOk();
        $response->assertSee('試験本番について');
        $response->assertDontSee('教材の不備について');
    }

    public function test_status_filter_matches_resolved_only(): void
    {
        $qaThread1 = QaThread::factory()->createCertificate()->create([
            'title' => '試験本番について',
            'status' => 'unresolved',
        ]);
        $qaThread2 = QaThread::factory()->createCertificate()->create([
            'title'=> '教材の不備について',
            'status' => 'resolved',
        ]);

        $student = $qaThread1->user;

        $response = $this->actingAs($student)->get(route('qa-board.index', ['status' => 'resolved']));

        $response->assertOk();
        $response->assertSee('教材の不備について');
        $response->assertDontSee('試験本番について');
    }
}
