<?php

namespace App\Events;

use App\Models\Message;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender', 'receiver');

    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->chat_id);

    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'user_id' => $this->message->user_id,
            'message' => $this->message->message,
            'sender' => $this->message->sender->name,
            'receiver' => $this->message->receiver->name,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
    public function broadcastAs()
    {
        return 'newMessage';
    }
}
