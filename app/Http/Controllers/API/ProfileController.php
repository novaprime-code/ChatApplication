<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Repositories\UserRepository;
use App\Traits\ResponseMessageTrait;
use Auth;

class ProfileController extends Controller
{
    use ResponseMessageTrait;
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Get Authenticated User Profile",
     *     tags={"Profile"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success response when profile data is fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="head", type="string", example="Data Fetched"),
     *                 @OA\Property(property="body", type="string", example="Profile fetched successfully")
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error response when profile data fetch fails",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="head", type="string", example="Failed to Fetch"),
     *                 @OA\Property(property="body", type="string", example="Profile fetch failed")
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {
        if ($me = $this->userRepository->authUser(Auth::id())) {
            $message = $this->responseMessage("Date Fetched", 'Profile fetched successfully');
            return response()->api(true, $message, $me, 200);
        } else {
            $message = $this->responseMessage('Failed to Fetch ', ' Profile fetch failed');
            return response()->api(false, $message, null, 200);
        }

    }

    /**
     * @OA\Patch(
     *     path="/api/update/{id}",
     *     summary="Update User Profile",
     *     tags={"Profile"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Profile data to be updated",
     *         @OA\JsonContent(ref="#/components/schemas/ProfileUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response when profile is updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="head", type="string", example="Profile Updated"),
     *                 @OA\Property(property="body", type="string", example="Profile updated successfully")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error response when profile update fails",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\Property(property="head", type="string", example="Update Failed"),
     *                 @OA\Property(property="body", type="string", example="Profile update failed")
     *             )
     *         )
     *     )
     * )
     */

    public function update(ProfileUpdateRequest $request, $id)
    {
        if ($this->userRepository->update($request->validated(), $id)) {
            $message = $this->responseMessage("Profile Updated", 'Profile updated successfully');
            return response()->api(true, $message, null, 200);
        } else {
            $message = $this->responseMessage('Update Failed', ' Profile update failed');
            return response()->api(false, $message, null, 200);
        }

    }
}
