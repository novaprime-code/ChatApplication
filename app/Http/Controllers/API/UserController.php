<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
    public function index()
    {
        if ($data = $this->userRepository->index()) {
            $message = $this->responseMessage("Data Fetched", 'User data fetched successfully');
            return response()->api(true, $message, $data, 200);
        } else {
            $message = $this->responseMessage("Data Fetched Failed", 'User data fetch failed');
            return response()->api(false, $message, null, 500);
        }
    }

}
