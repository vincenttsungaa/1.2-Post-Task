<?php

namespace App\Repositories\ExpenseManagementRepositories;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str; 
use App\Service;
use App\Category;
use Illuminate\Support\Arr;

class CategoryManagementRepository extends Controller
{


    public function getCategories()
    {
        $records = Category::get();
        return $records->toJson();
    }

    public function registerCategory($request)
    {

        $checkCount = Category::where('categoryName', '=', $request['expenseCategoryName'])
                        ->get()
                        ->count();
        if($checkCount > 0)
        {
            return response()->json(['Category Already Exists'], 400);
        }

        $input['categoryName'] = $request['expenseCategoryName'];
        $input['categoryDescription'] = $request['expenseCategoryDescription'];
        $user = Category::create($input);

        return response()->json(['SUCCESS'],201);

    }

    public function updateCategory($request)
    {
        $expenseCategoryName = $request['expenseCategoryName'];
        $expenseCategoryDescription = !empty($request['expenseCategoryDescription']) ? $request['expenseCategoryDescription'] : '';

        //Checks if Category Exists
        $categoryCount = Category::where('categoryName', '=', $expenseCategoryName)
                        ->get()
                        ->count();
        if($categoryCount == 0)
        {
            return response()->json(['Category Does Not Exist'], 400);
        }

        // Updates Category Description
        if(!empty($request['expenseCategoryDescription']))
        {
            $dataUpdated = Category::where('categoryName', '=', $expenseCategoryName)
                            ->update(['categoryDescription' => $expenseCategoryDescription]);
            
            return response()->json(['Category Description Updated'], 200);
        }


        return response()->json(['Category Information Not Updated'], 200);
    }

    public function deleteCategory($request)
    {
        $expenseCategoryName = $request['expenseCategoryName'];

        //Checks if Category Exists
        $categoryCount = Category::where('categoryName', '=', $expenseCategoryName)
                        ->get()
                        ->count();
        if($categoryCount == 0)
        {
            return response()->json(['Category Does Not Exist'], 400);
        }

        $dataDelete = Category::where('categoryName', '=', $expenseCategoryName)->delete();
        
        //Convert Response to SOAP
        return response()->json(['Category '. $expenseCategoryName .' Has Been Deleted'], 200);

    }
}