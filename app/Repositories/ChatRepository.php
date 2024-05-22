<?php

namespace App\Repositories;

use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatRepository implements ChatRepositoryInterface
{
    /**
     * Get all chats of Auth User
     *
     * @return JsonResource
     */
    public function all(): JsonResource
    {
        $chats = Chat::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->get();
        $chats = ChatResource::collection($chats);
        return $chats;
    }

    /**
     * Create chat
     *
     * @param array<string, mixed> $data
     * @return Chat
     */
    public function create(array $data): Chat
    {

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $data['receiver_id'],
        ]);

        return $chat;

    }

    /**
     * Delete chat
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Chat::where('id', $id)->delete();
    }
}
