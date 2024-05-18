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
    public function view()
    {
        $users = User::all();
        $message = $this->responseMessage("Hedding", "Message Body");
        return response()->api(true, $message, $users, 201);
    }
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
