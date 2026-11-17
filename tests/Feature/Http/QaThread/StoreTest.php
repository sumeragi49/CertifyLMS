<?php

namespace Tests\Feature\Http\QaThread;

use App\Models\User;
use App\Models\QaThread;
use App\Models\Certification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_store_qa_thread(): void
    {
        $student = User::factory()->student()->create();

        $certification = Certification::factory()->create();

        $qaThread = [
            'certification_id' => $certification->id,
            'title' => '問題の難易度に関して',
            'body' => '難易度ごとにできる問題を変更することは可能ですか？',
        ];

        $response = $this->actingAs($student)->post(route('qa-board.store'),$qaThread);

        $newThread = QaThread::latest('id')->first();

        $response->assertRedirect(route('qa-board.show', ['thread' => $newThread]));

        $this->assertDatabaseHas('qa_threads', [
            'user_id' => $student->id,
            'certification_id' => $certification->id,
            'title' => $newThread->title,
            'body' => $newThread->body,
            'status' => 'unresolved',
        ]);
    }

    public function test_student_cannot_store_qa_thread_certification_id_required(): void
    {
        $student = User::factory()->student()->create();

        $certification = Certification::factory()->create();

        $qaThread = [
            'certification_id' => '',
            'title' => '問題の難易度に関して',
            'body' => '難易度ごとにできる問題を変更することは可能ですか？',
        ];

        $response = $this->actingAs($student)->post(route('qa-board.store'),$qaThread);

        $response->assertSessionHasErrors('certification_id');
    }

    public function test_student_cannot_store_qa_thread_title_required(): void
    {
        $student = User::factory()->student()->create();

        $certification = Certification::factory()->create();

        $qaThread = [
            'certification_id' => $certification->id,
            'title' => '',
            'body' => '難易度ごとにできる問題を変更することは可能ですか？',
        ];

        $response = $this->actingAs($student)->post(route('qa-board.store'),$qaThread);

        $response->assertSessionHasErrors('title');
    }

    public function test_student_cannot_store_qa_thread_body_required(): void
    {
        $student = User::factory()->student()->create();

        $certification = Certification::factory()->create();

        $qaThread = [
            'certification_id' => $certification->id,
            'title' => '問題の難易度に関して',
            'body' => '',
        ];

        $response = $this->actingAs($student)->post(route('qa-board.store'), $qaThread);

        $response->assertSessionHasErrors('body');
    }
}
