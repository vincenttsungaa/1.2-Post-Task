<?php

namespace App\Http\Controllers\UserManagementControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Validator;
use App\Repositories\UserManagementRepositories\UserManagementRepository;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class UserManagementController extends Controller
{

    protected $userRepo;

    public function __construct(UserManagementRepository $userRepo)
    {
        $this->repo = $userRepo;
    }

    public function UserManagementProcess(Request $request)
    {

        $Validation = $this->validate($request, [
            'Category' => 'Required',
            'name' => Rule::requiredif($request->Category == 'Register'),
            'email' => Rule::requiredif($request->Category == 'Register').'||'.
                        Rule::requiredif($request->Category == 'Update').'||'.
                        Rule::requiredif($request->Category == 'Delete'),
            'password' => Rule::requiredif($request->Category == 'Register'),
            'status' => 'Nullable'
        ]);

        $category = $request->Category;

        if($category == 'Get'){
            $getResponse = $this->repo->getUsers();
            return $getResponse;
        }

        if ($category == 'Register') {
            $registerResponse = $this->repo->registerUser($request->all());
            return $registerResponse;
        }

        if ($category == 'Update') {
            $updateResponse = $this->repo->updateUser($request->all());
            return $updateResponse;
        }

        if ($category == 'Delete') {
            $deleteResponse = $this->repo->deleteUser($request->all());
            return $deleteResponse;
        }


    }
}
