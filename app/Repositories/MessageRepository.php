<?php

namespace App\Repositories;

use App\Events\MessageSent;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use DB;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class MessageRepository implements MessageRepositoryInterface
{
    /**
     * Get all messages of chat
     *
     * @param int $chatId
     * @return JsonResource
     */
    public function all(int $chatId): JsonResource
    {
        $messages = Message::where('chat_id', $chatId)->get();
        return MessageResource::collection($messages);
    }

    /**
     * Create message
     *
     * @param array<string, mixed> $data
     * @return JsonResource
     */
    public function create(array $data): JsonResource
    {Log::info($data);
        DB::beginTransaction();
        try {
            $message = Message::create([
                'chat_id' => $data['chat_id'],
                'sender_id' => $data['sender_id'],
                'receiver_id' => $data['receiver_id'],
                'message' => $data['message'],
            ]);
            event(new MessageSent($message));
            DB::commit();
            return new MessageResource($message);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }}

    /**
     * Delete message
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Message::where('id', $id)->delete();
    }
}
