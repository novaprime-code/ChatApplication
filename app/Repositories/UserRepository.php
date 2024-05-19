<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use DB;
use Hash;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{

    public function index()
    {

        DB::beginTransaction();
        try {
            $user = User::all();
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }

    }
    public function register($request)
    {

        DB::beginTransaction();
        try {
            User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'phone_number' => $request['phone_number'],
                'address' => $request['address'],
                'dob' => $request['dob'],
                'gender' => $request['gender'],

            ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }

    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone_number' => $request['phone_number'],
                'address' => $request['address'],
                'dob' => $request['dob'],
                'gender' => $request['gender'],
            ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
    }

    public function authUser($id)
    {

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return false;

        }
    }
}
