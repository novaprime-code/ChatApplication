<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseMessageTrait
{

    public function responseMessage($head, $body)
    {
        return [
            'head' => $head,
            'body' => $body,
        ];
    }

    public function serverFailedMessage(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => [
                'head' => 'Server Error',
                'body' => 'Something went wrong. Please try again later.',
            ],
        ], 500);
    }
}
