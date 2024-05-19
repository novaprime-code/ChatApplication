<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\ResponseMessageTrait;

class RegisterController extends Controller
{

    use ResponseMessageTrait;
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="User Registration",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Request body for user registration",
     *         @OA\JsonContent(ref="#/components/schemas/UserRegistrationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response when user registration is completed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="head", type="string", example="Registration Completed"),
     *                 @OA\Property(property="body", type="string", example="Your account has been registered successfully.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error response when user registration fails",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="head", type="string", example="Error"),
     *                 @OA\Property(property="body", type="string", example="User registration failed")
     *             )
     *         )
     *     )
     * )
     */

    public function store(UserRegistrationRequest $request)
    {
        if ($this->userRepository->register($request->validated())) {
            return response()->api(true, [
                "head" => "Registration Completed",
                "body" => "Your account has been registered successfully.",
            ], null, 200);
        } else {
            $message = $this->responseMessage(
                "Error",
                "User registration failed"
            );
            return response()->api(false, $message, null, 500);
        }

    }
}
