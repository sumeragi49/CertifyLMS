<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ReplySeeder extends Seeder
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

        $thread = Thread::query()
            ->where('user_id', $student->id)
            ->where('status', 'resolved')
            ->orderBy('created_at')
            ->first();
        if ($thread === null) {
            return;
        }

        $param = [
            'id' => (string) Str::ulid(),
            'user_id' => $student->id,
            'thread_id' => $thread->id,
            'body' => '定期的な小テストで目標点数以下が続く場合,模擬試験を受けられないなどの制約はありません。ただしこちら側から「もう少し学習を重ねた方が良い」などのアドバイスはさせていただきます。',
        ];
        DB::table('replies')->insert($param);
    }
}
