<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use DB;
use Hash;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{
/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *     @OA\Property(property="phone_number", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St, Springfield, IL"),
 *     @OA\Property(property="gender", type="string", example="male"),
 *     @OA\Property(property="dob", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
 * )
 */

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

    /**
     * @OA\Schema(
     *     schema="ProfileUpdateRequest",
     *     required={"name", "email", "password", "phone_number", "address", "gender", "dob"},
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="email", type="string", format="email"),
     *     @OA\Property(property="password", type="string", format="password"),
     *     @OA\Property(property="phone_number", type="string"),
     *     @OA\Property(property="address", type="string"),
     *     @OA\Property(property="gender", type="string", enum={"male", "female"}),
     *     @OA\Property(property="dob", type="string", format="date"),
     * )
     */

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
