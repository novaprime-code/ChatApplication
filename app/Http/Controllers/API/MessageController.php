<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use App\Traits\ResponseMessageTrait;
use Auth;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use ResponseMessageTrait;
    private $messageRepository;

    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

/**
 * @OA\Post(
 *     path="/api/chats/{chatId}/messages",
 *     summary="Send a message in a chat",
 *     tags={"Message"},
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

    public function store(Request $request, $chatId)
    {
        $validatedData = $request->validate([
            'message' => 'required|string',
        ]);

        $chat = Chat::findOrFail($chatId);

        if ($chat->sender_id !== Auth::id() && $chat->receiver_id !== Auth::id()) {
            return response()->json(['message' => 'Not authorized to send messages in this chat'], 403);
        }
        $validatedData = [
            'chat_id' => intval($chatId),
            'sender_id' => Auth::id(),
            'receiver_id' => $chat->sender_id === Auth::id() ? $chat->receiver_id : $chat->sender_id,
            'message' => $validatedData['message'],
        ];
        if ($data = $this->messageRepository->create($validatedData)) {
            $message = $this->responseMessage("Message Sent", 'Your message was sent successfully');
            return response()->api(true, $message, $data, 200);
        } else {
            $message = $this->responseMessage("Message Sent Failed", 'Your message was not sent');
            return response()->api(false, $message, null, 500);
        }
    }

/**
 * @OA\Get(
 *     path="/api/chats/{chatId}/messages",
 *     summary="Get chat history",
 *     tags={"Message"},
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

    public function index($chatId)
    {
        $chat = Chat::findOrFail($chatId);

        if ($chat->sender_id !== Auth::id() && $chat->receiver_id !== Auth::id()) {
            return response()->json(['message' => 'Not authorized to view this chat'], 403);
        }
        if ($data = $this->messageRepository->all($chatId)) {
            $message = $this->responseMessage("Chats Fetched", 'Your chat fetched successfully');
            return response()->api(true, $message, $data, 200);
        } else {
            $message = $this->responseMessage("Chat Fetched Failed", 'Your chat fetch failed');
            return response()->api(false, $message, null, 500);
        }

    }
}
