<?php

namespace App\Http\Controllers\API;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use App\Traits\ResponseMessageTrait;
use Auth;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use ResponseMessageTrait;
    private $chatRepository;

    public function __construct(ChatRepositoryInterface $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }
    public function index($userId)
    {
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('user_id', Auth::id())->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('receiver_id', Auth::id());
        })->with('sender', 'receiver')->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => $message], 201);
    }
}
