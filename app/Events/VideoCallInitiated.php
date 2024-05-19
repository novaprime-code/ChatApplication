<?php

namespace App\Events;

use App\Models\VideoCall;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoCallInitiated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $videoCall;

    public function __construct(VideoCall $videoCall)
    {
        $this->videoCall = $videoCall;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('video-call.' . $this->videoCall->id);
    }

    public function broadcastAs()
    {
        return 'callInitiated';
    }
}
