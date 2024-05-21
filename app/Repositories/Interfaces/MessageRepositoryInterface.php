<?php

namespace App\Repositories\Interfaces;

use App\Models\Message;
use Illuminate\Http\Resources\Json\JsonResource;

interface MessageRepositoryInterface
{
    /**
     * Get all messages of chat
     *
     * @param int $chatId
     * @return JsonResource
     */
    public function all(int $chatId): JsonResource;

    /**
     * Create message
     *
     * @param array<string, mixed> $data
     * @return JsonResource
     */
    public function create(array $data): JsonResource;

    /**
     * Delete message
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
