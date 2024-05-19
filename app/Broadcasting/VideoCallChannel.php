<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\VideoCall;

class VideoCallChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join($user, $callId)
    {
        $videoCall = VideoCall::findOrFail($callId);

        if ($videoCall->initiator_id === $user->id || $videoCall->recipient_id === $user->id) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }

        return [];
    }
}
