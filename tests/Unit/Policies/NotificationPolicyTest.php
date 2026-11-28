<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\InfoNotification;
use App\Policies\NotificationPolicy;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class NotificationPolicyTest extends TestCase
{
    public function test_user_can_perform_all_notifications(): void
    {
        $admin = User::factory()->admin()->create();
        $coach = User::factory()->coach()->create();
        $student = User::factory()->student()->create();
        $policy = new NotificationPolicy;

        foreach ([$admin, $coach, $student] as $user) {
            $notification = InfoNotification::factory()->create([
                'notifiable_id' => $user->id,
            ]);

            $this->assertTrue($policy->viewAny($user));
            $this->assertTrue($policy->read($user, $notification));
            $this->assertTrue($policy->allRead($user));
        }
    }

}
