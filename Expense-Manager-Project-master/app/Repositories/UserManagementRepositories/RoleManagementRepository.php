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
use App\Role;
use Illuminate\Support\Arr;

class RoleManagementRepository extends Controller
{


    public function getRoles()
    {
        $records = Role::get();
        return $records->toJson();
    }

    public function registerRole($request)
    {

        $checkCount = Role::where('roleName', '=', $request['roleName'])
                        ->get()
                        ->count();
        if($checkCount > 0)
        {
            return response()->json(['Role Already Exists'], 400);
        }

        $input = $request;
        $user = Role::create($input);

        return response()->json(['SUCCESS'],201);

    }

    public function updateRole($request)
    {
        $roleName = $request['roleName'];
        $roleDescription = !empty($request['roleDescription']) ? $request['roleDescription'] : '';

        // Checks if Role to be updated is Admin
        if(strtolower($roleName) == 'administrator')
        {
            return response()->json(['Cannot Update Administrator'], 400);
        }

        //Checks if Role Exists
        $role = Role::where('roleName', '=', $roleName)
                        ->get()
                        ->count();
        if($role == 0)
        {
            return response()->json(['Role Does Not Exist'], 400);
        }

        // Updates Role Description
        if(!empty($request['roleDescription']))
        {
            $dataUpdated = Role::where('roleName', '=', $roleName)
                            ->update(['roleDescription' => $roleDescription]);
            
            return response()->json(['Role Description Updated'], 200);
        }


        return response()->json(['Role Information Not Updated'], 200);
    }

    public function deleteRole($request)
    {
        $roleName = $request['roleName'];

        // Checks if Role to be deleted is Admin
        if(strtolower($roleName) == 'administrator')
        {
            return response()->json(['Cannot Delete Administrator'], 400);
        }

        //Checks if Role Exists
        $role = Role::where('roleName', '=', $roleName)
                        ->get()
                        ->count();
        if($role == 0)
        {
            return response()->json(['Role Does Not Exist'], 400);
        }

        $dataDelete = Role::where('roleName', '=', $roleName)->delete();
        
        //Convert Response to SOAP
        return response()->json(['Role '. $roleName .' Has Been Deleted'],200);

    }
}