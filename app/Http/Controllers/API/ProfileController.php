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
