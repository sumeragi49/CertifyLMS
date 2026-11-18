<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\QaThread;
use App\Models\QaReply;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QaReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::query()->where('email', 'student@certify-lms.test')    ->first();

        if ($student === null) {
            $this->command?->warn('QaThreadReplySeeder: 受講生 User が存在しません。先に UserSeeder を実行してください。');
        }

        $coach = User::query()->where('email', 'coach@certify-lms.test')  ->first();

        if ($coach === null) {
            $this->command?->warn('QaThreadReplySeeder: コーチ Coach が存在しません。先に UserSeeder を実行してください。');
        }

        $thread = QaThread::query()
                ->where('status', 'resolved')
                ->orderBy('created_at')
                ->first();
        
        if (empty($thread)) {
            $this->command?->warn('QaThreadSeeder: スレッドが存在しません。先に QaThreadSeeder を実行してください。');

            return;
        }

        $this->createReply($student, $coach, $thread);
    }

    private function createReply(User $student, $coach, $thread): array
    {
        $data = [
            [
                'user_id' => $student->id,
                'body' => "自己解決できました。ご対応ありがとうございます。",
            ],
            [
                'user_id' => $coach->id,
                'body' => "追加の課題などは特にないですが自己学習を継続してください",
            ],
            [
                'user_id' => $coach->id,
                'body' => "2026年10月1日から追加で課題の提出が決まりました。後ほどご連絡いたします。",
            ],
            
        ];

        $threads = [];
        foreach ($data as $row) {
            $threads[] = QaReply::factory()
                ->state([
                    'user_id' => $row['user_id'],
                    'qa_thread_id' => $thread->id,
                    'body' => $row['body'],
                ])
                ->create();
        }

        return $threads;
    }
}
