<?php

namespace App\Http\Controllers\API;

use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use App\Traits\ResponseMessageTrait;
use Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    use ResponseMessageTrait;
    private $chatRepository;

    public function __construct(ChatRepositoryInterface $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }
    public function initChat(Request $request)
    {
        $validatedData = $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validatedData['receiver_id'],
        ]);

        return response()->json($chat);
    }

    public function sendMessage(Request $request, $chatId)
    {
        $validatedData = $request->validate([
            'message' => 'required|string',
        ]);

        $chat = Chat::findOrFail($chatId);

        if ($chat->sender_id !== Auth::id() && $chat->receiver_id !== Auth::id()) {
            return response()->json(['message' => 'Not authorized to send messages in this chat'], 403);
        }

        $message = $chat->messages()->create([
            'sender_id' => Auth::id(),
            'receiver_id' => $chat->sender_id === Auth::id() ? $chat->receiver_id : $chat->sender_id,
            'message' => $validatedData['message'],
        ]);

        event(new NewChatMessage($message));

        return response()->json($message);
    }

    public function getChatHistory($chatId)
    {
        $chat = Chat::findOrFail($chatId);

        if ($chat->sender_id !== Auth::id() && $chat->receiver_id !== Auth::id()) {
            return response()->json(['message' => 'Not authorized to view this chat'], 403);
        }

        $messages = $chat->messages()->with('sender', 'receiver')->get();

        return response()->json($messages);
    }
}
