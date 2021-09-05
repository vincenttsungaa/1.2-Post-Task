<?php

namespace App\Repositories\UserManagementRepositories;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str; 
use App\Service;
use App\User;
use Illuminate\Support\Arr;

class UserManagementRepository extends Controller
{

    public function getUsers()
    {
        $records = User::get();
        return $records->toJson();
    }

    public function registerUser($request)
    {

        $checkCount = User::where('email', '=', $request['email'])
                        ->get()
                        ->count();
        if($checkCount > 0)
        {
            return response()->json(['User Already Exists'], 400);
        }

        $input = $request;
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);


        return response()->json(['SUCCESS'],201);

    }

    public function updateUser($request)
    {
        $userEmail = $request['email'];
        $userName = !empty($request['name']) ? $request['name'] : '';
        $userPassword = !empty($request['password']) ? $request['password'] : '';
        $userRole = !empty($request['role']) ? $request['role'] : '';
        $userStatus = !empty($request['status']) ? $request['status'] : '';

        //Checks if User Exists
        $emailExists = User::where('email', '=', $userEmail)
                        ->get()
                        ->count();
        if($emailExists == 0)
        {
            return response()->json(['User is not Registered'], 400);
        }

        if(!empty($request['name']))
        {
            $dataUpdated = User::where('email', '=', $userEmail)
                            ->update(['name' => $userName]);
        }
        if(!empty($request['password']))
        {
            $dataUpdated = User::where('email', '=', $userEmail)
                            ->update(['password' => $userPassword]);
        }
        if(!empty($request['role']))
        {
            $dataUpdated = User::where('email', '=', $userEmail)
                            ->update(['role' => $userRole]);
        }
        if(!empty($request['status']))
        {
            $dataUpdated = User::where('email', '=', $userEmail)
                            ->update(['status' => $userStatus]);
        }


        return response()->json(['User Information Updated'], 200);
    }

    public function deleteUser($request)
    {
        $userEmail = $request['email'];

        //Checks if User Exists
        $emailExists = User::where('email', '=', $userEmail)
                        ->get()
                        ->count();
        if($emailExists == 0)
        {
            return response()->json(['User is not Registered'], 400);
        }

        $dataDelete = User::where('email', '=', $userEmail)->delete();
        
        //Convert Response to SOAP
        return response()->json(['User '. $userEmail .' Has Been Deleted'],200);

    }
}