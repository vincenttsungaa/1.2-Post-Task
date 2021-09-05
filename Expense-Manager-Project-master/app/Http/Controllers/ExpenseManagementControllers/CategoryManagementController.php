<?php

namespace App\Http\Controllers\ExpenseManagementControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ExpenseManagementRepositories\CategoryManagementRepository;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class CategoryManagementController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryManagementRepository $categoryRepo)
    {
        $this->repo = $categoryRepo;
    }

    public function CategoryManagementProcess(Request $request)
    {

       $Validation = $this->validate($request, [
            'Category' => 'Required',
            'expenseCategoryName' => Rule::requiredif($request->Category == 'Register'),
            'expenseCategoryDescription' => Rule::requiredif($request->Category == 'Register'),
        ]);

        $category = $request->Category;

        if($category == 'Get'){
            $getResponse = $this->repo->getCategories();
            return $getResponse;
        }
        if ($category == 'Register') {
            $registerResponse = $this->repo->registerCategory($request->all());
            return $registerResponse;
        }

        if ($category == 'Update') {
            $updateResponse = $this->repo->updateCategory($request->all());
            return $updateResponse;
        }

        if ($category == 'Delete') {
            $deleteResponse = $this->repo->deleteCategory($request->all());
            return $deleteResponse;
        }


    }
}
