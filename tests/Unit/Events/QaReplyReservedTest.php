<?php

namespace Tests\Unit\Events;

use App\Models\QaReply;
use App\Events\QaReplyReserved;
use Illuminate\Broadcasting\PrivateChannel;
use Tests\TestCase;

class QaReplyReservedTest extends TestCase
{
    public function test_broadcast_on_targets_reply_private_channel(): void
    {
        // Arrange
        $reply = QaReply::factory()->create();
        $event = new QaReplyReserved($reply);

        // Act
        $channel = $event->broadcastOn();

        // Assert
        $this->assertInstanceOf(PrivateChannel::class, $channel);
        $this->assertSame("private-qa-thread.index", $channel->name, 'Reply 単位の Private Channel (private- prefix 付与) に送るはず');
    }

    public function test_broadcast_as_returns_event_alias_reply(): void
    {
        // Arrange
        $reply = QaReply::factory()->create();
        $event = new QaReplyReserved($reply);

        // Act
        $alias = $event->broadcastAs();

        // Assert
        $this->assertSame('ReplyReserved', $alias);
    }
}
