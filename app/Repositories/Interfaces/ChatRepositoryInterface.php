<?php

namespace App\Repositories\Interfaces;

use App\Models\Chat;
use Illuminate\Http\Resources\Json\JsonResource;

interface ChatRepositoryInterface
{
    /**
     * Get all chats of Auth User
     *
     * @return Chat[]
     */
    public function all(): JsonResource;

    /**
     * Create chat
     *
     * @param array<string, mixed> $data
     * @return Chat
     */
    public function create(array $data): Chat;

    /**
     * Delete chat
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
