<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\ResponseMessageTrait;

class UserController extends Controller
{
    use ResponseMessageTrait;
    private $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/home",
     *     summary="Fetch All Users",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Success response when user data is fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="head", type="string", example="Data Fetched"),
     *                 @OA\Property(property="body", type="string", example="User data fetched successfully")
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error response when user data fetch fails",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="head", type="string", example="Data Fetch Failed"),
     *                 @OA\Property(property="body", type="string", example="User data fetch failed")
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {
        if ($data = $this->userRepository->index()) {
            $message = $this->responseMessage("Data Fetched", 'User data fetched successfully');
            return response()->api(true, $message, UserResource::collection($data), 200);
        } else {
            $message = $this->responseMessage("Data Fetched Failed", 'User data fetch failed');
            return response()->api(false, $message, null, 500);
        }
    }

}
