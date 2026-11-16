<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserRole;
use App\Enums\QaThreadStatus;
use App\Models\User;
use App\Models\Certification;
use App\Models\QaThread;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QaThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::query()
            ->where('role', UserRole::Student->value)
            ->orderBy('created_at')
            ->first();

        if ($student === null) {
            $this->command?->warn('QaThreadSeeder: 受講生 User が存在しません。先に UserSeeder を実行してください。');

            return;
        }

        $certification = Certification::with('certificates')
                           -> where('status', 'published')
                           -> first();

        if (empty($certification)) {
            $this->command?->warn('CertificationSeeder: 資格が存在しません。先に CertificationSeeder を実行してください。');

            return;
        }

        $this->createThread($student, $certification);
    }

    private function createThread(User $student, $certification): array
    {
        $data = [
            [
                'title' => '模擬試験について',
                'body' => "セクションのテストが一定以上でないと模試を受けられないなど制限はありますか？",
                'status' => QaThreadStatus::Unresolved,
                'resolved_at' => null,
            ],
            [
                'title' => '追加の課題について',
                'body' => "セクションのテストが一定以上でないと追加で課題が出たりしますか？",
                'status' => QaThreadStatus::Unresolved,
                'resolved_at' => null,
            ],
            [
                'title' => '追加の課題について',
                'body' => "セクションのテストが一定以上でないと追加で課題が出たりしますか？",
                'status' => QaThreadStatus::Resolved,
                'resolved_at' => now(),
            ],
            [
                'title' => '模擬試験と試験について',
                'body' => "模擬試験の点数が何点以上でないと実際の試験が受けられないなどありますか？",
                'status' => QaThreadStatus::Unresolved,
                'resolved_at' => null,
            ],
            [
                'title' => '模擬試験と試験について',
                'body' => "模擬試験の点数が何点以上でないと実際の試験が受けられないなどありますか？",
                'status' => QaThreadStatus::Unresolved,
                'resolved_at' => null,
            ],
        ];

        $threads = [];
        foreach ($data as $row) {
            $threads[] = QaThread::factory()
                ->state([
                    'user_id' => $student->id,
                    'certification_id' => $certification->id,
                    'title' => $row['title'],
                    'body' => $row['body'],
                    'status' => $row['status']->value,
                    'resolved_at' => $row['resolved_at'],
                ])
                ->create();
        }

        return $threads;
    }
}
