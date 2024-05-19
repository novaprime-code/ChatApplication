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

    /**
     * @OA\Post(
     *     path="/api/chats",
     *     summary="Initialize Chat",
     *     tags={"Chat"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data required to initialize a chat",
     *         @OA\JsonContent(
     *             required={"receiver_id"},
     *             @OA\Property(property="receiver_id", type="integer", description="ID of the receiver user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful initialization of chat",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID of the newly created chat"),
     *             @OA\Property(property="sender_id", type="integer", description="ID of the sender user"),
     *             @OA\Property(property="receiver_id", type="integer", description="ID of the receiver user"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the chat was created"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the chat was last updated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error when required data is missing or invalid",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", description="Validation errors", example={"receiver_id": {"The receiver id field is required."}})
     *         )
     *     )
     * )
     */

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
/**
 * @OA\Post(
 *     path="/api/chats/{chatId}/messages",
 *     summary="Send a message in a chat",
 *     tags={"Chat"},
 *     security={{ "sanctum": {} }},
 *     @OA\Parameter(
 *         name="chatId",
 *         in="path",
 *         required=true,
 *         description="ID of the chat",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Message data",
 *         @OA\JsonContent(
 *             required={"message"},
 *             @OA\Property(property="message", type="string", example="Hello, how are you?")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Message sent successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", description="ID of the sent message"),
 *             @OA\Property(property="sender_id", type="integer", description="ID of the message sender"),
 *             @OA\Property(property="receiver_id", type="integer", description="ID of the message receiver"),
 *             @OA\Property(property="message", type="string", description="Content of the message"),
 *             @OA\Property(property="created_at", type="string", description="Timestamp when the message was sent")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Not authorized to send messages in this chat",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not authorized to send messages in this chat")
 *         )
 *     )
 * )
 */

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

/**
 * @OA\Get(
 *     path="/api/chats/{chatId}/messages",
 *     summary="Get chat history",
 *     tags={"Chat"},
 *     security={{ "sanctum": {} }},
 *     @OA\Parameter(
 *         name="chatId",
 *         in="path",
 *         required=true,
 *         description="ID of the chat",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Chat history retrieved successfully",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="sender_id", type="integer"),
 *                 @OA\Property(property="receiver_id", type="integer"),
 *                 @OA\Property(property="message", type="string"),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time"),
 *                 @OA\Property(property="sender", type="object",
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="name", type="string"),
 *                 ),
 *                 @OA\Property(property="receiver", type="object",
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="name", type="string"),
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Not authorized to view this chat",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not authorized to view this chat")
 *         )
 *     )
 * )
 */

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
