<?php

namespace App\Http\Controllers\Api;

use App\Events\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        return api_response(true, '', UserResource::collection(User::paginate(10)));
    }

    public function show(User $user)
    {
        return api_response(true, '', new UserResource($user));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        try {
            $data_transactions = DB::transaction(function () use ($data, $request) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    // just won't remove it from user table it's not effects on result
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                ]);

                // send array of ids roles if its empty will not attach any roles
                // [1,2,3]
                if(isset($request->roles)){
                    $user->Roles()->attach($request->roles);
                }
                return $user;
            });

            event(new CreateUser($data_transactions));

        } catch (\Exception $e) {
            return api_response(false, 'Connection error', null , 500);
        }

        return api_response(true, 'User Added Successfully', new UserResource($data_transactions));
    }

    public function update(UpdateUserRequest $request , User $user)
    {
        $data = $request->validated();

        try {
            $data_transactions = DB::transaction(function () use ($user, $data, $request) {
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]);
                // send array of ids roles if its empty will remove all roles
                // [1,2,3]
                $user->Roles()->sync($request->roles);
                return $user;
            });
        } catch (\Exception $e) {
            return api_response(false, 'Connection error', null , 500);
        }

        return api_response(true, 'User Updated Successfully', new UserResource($data_transactions));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return api_response(true, 'User Deleted Successfully');
    }
}
