<?php

namespace App\Broadcasting;

class ChatChannel
{
    public function join($user, $chatId)
    {
        $chat = \App\Models\Chat::findOrFail($chatId);

        if ($chat->sender_id === $user->id || $chat->receiver_id === $user->id) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }
        return [];
    }

    public function validUser($user)
    {
        return true;
    }
}
