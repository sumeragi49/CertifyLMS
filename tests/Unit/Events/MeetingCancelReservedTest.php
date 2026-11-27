<?php

namespace Tests\Unit\Events;

use App\Models\Meeting;
use App\Events\MeetingCancelReserved;
use Illuminate\Broadcasting\PrivateChannel;
use Tests\TestCase;

class MeetingCancelReservedTest extends TestCase
{
    public function test_broadcast_on_targets_meeting_cancel_private_channel(): void
    {
        // Arrange
        $meeting = Meeting::factory()->create();
        $event = new MeetingCancelReserved($meeting);

        // Act
        $channel = $event->broadcastOn();

        // Assert
        $this->assertInstanceOf(PrivateChannel::class, $channel);
        $this->assertSame("private-meeting.index", $channel->name, 'Meeting 単位の Private Channel (private- prefix 付与) に送るはず');
    }

    public function test_broadcast_as_returns_event_alias_meeting_cancel(): void
    {
        // Arrange
        $meeting = Meeting::factory()->create();
        $event = new MeetingCancelReserved($meeting);

        // Act
        $alias = $event->broadcastAs();

        // Assert
        $this->assertSame('MeetingCancel', $alias);
    }
}
