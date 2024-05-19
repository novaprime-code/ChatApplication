<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Repositories\UserRepository;

class ProfileController extends Controller
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function update(ProfileUpdateRequest $request, $id)
    {
        if ($this->userRepository->update($request->validated(), $id)) {
            return response()->api(true, 'Profile updated successfully');
        } else {
            return response()->api(false, 'Profile update failed');
        }

    }
}
