<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Repositories\ChatRepository;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use App\Traits\ResponseMessageTrait;
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
     * @OA\Get(
     *     path="/api/chats",
     *     summary="Get all chats",
     *     tags={"Chat"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="List of chats",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="ID of the chat"),
     *                 @OA\Property(property="sender_id", type="integer", description="ID of the sender user"),
     *                 @OA\Property(property="receiver_id", type="integer", description="ID of the receiver user"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the chat was created"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the chat was last updated"),
     *                 @OA\Property(property="sender", type="object",
     *                     @OA\Property(property="id", type="integer", description="ID of the sender user"),
     *                     @OA\Property(property="name", type="string", description="Name of the sender user")
     *                 ),
     *                 @OA\Property(property="receiver", type="object",
     *                     @OA\Property(property="id", type="integer", description="ID of the receiver user"),
     *                     @OA\Property(property="name", type="string", description="Name of the receiver user")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        if ($data = $this->chatRepository->all()) {
            $message = $this->responseMessage("Chats Fetched", 'Your chat fetched successfully');
            return response()->api(true, $message, $data, 200);
        } else {
            $message = $this->responseMessage("Chat Fetched Failed", 'Your chat fetch failed');
            return response()->api(false, $message, null, 500);
        }

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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        if ($chat = $this->chatRepository->create($validatedData)) {
            $message = $this->responseMessage("Chat Initialized Successfully", 'Your chat has been initialized successfully');
            return response()->api(true, $message, new ChatResource($chat), 200);
        } else {
            $message = $this->responseMessage("Chat Creation Failed", 'Your chat creation failed');
            return response()->api(false, $message, null, 500);
        }
    }

    //destroy
    /**
     * @OA\Delete(
     *     path="/api/chats/{chatId}",
     *     summary="Delete Chat",
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
     *         description="Chat deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chat deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to delete this chat",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not authorized to delete this chat")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chat not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chat not found")
     *         )
     *     )
     * )
     */
    public function destroy($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        if ($chat->sender_id !== auth()->id() && $chat->receiver_id !== auth()->id()) {
            $message = $this->responseMessage("Chat Deletion Failed", 'You are not authorized to delete this chat');
            return response()->api(false, $message, null, 403);
        }
        if ($this->chatRepository->delete($chatId)) {
            $message = $this->responseMessage("Chat Deleted", 'Your chat has been deleted successfully');
            return response()->api(true, $message, null, 200);
        } else {
            $message = $this->responseMessage("Chat Deletion Failed", 'Your chat deletion failed');
            return response()->api(false, $message, null, 500);
        }
    }

}
