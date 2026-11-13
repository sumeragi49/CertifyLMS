<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $student = User::query()->where('email', 'student@certify-lms.test')->first();
        if ($student === null) {
            return;
        }

        $certification = Certification::query()
            ->where('name', '基本情報技術者試験')
            ->first();
        if ($certification === null) {
            return;
        }

        $param = [
            'id' => (string) Str::ulid(),
            'user_id' => $student->id,
            'certification_id' => $certification->id,
            'title' => '模擬試験後について',
            'body' => '模擬試験で目標点数に達しない場合再試験などを受けることは可能ですか',
            'status' => 'resolved',
            'resolved_at' => now(),
        ];
        DB::table('qa_threads')->insert($param);

        $param = [
            'id' => (string) Str::ulid(),
            'user_id' => $student->id,
            'certification_id' => $certification->id,
            'title' => '模擬試験の受験の可否について',
            'body' => '定期的な小テストで目標点数以下が続く場合,模擬試験を受けられないなどの制約はありますか。',
            'status' => 'unresolved',
        ];
        DB::table('qa_threads')->insert($param);
    }
}
