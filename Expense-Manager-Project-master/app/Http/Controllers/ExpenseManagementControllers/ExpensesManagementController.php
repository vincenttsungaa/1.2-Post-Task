<?php

namespace App\Http\Controllers\ExpenseManagementControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ExpenseManagementRepositories\ExpensesManagementRepository;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class ExpensesManagementController extends Controller
{
    protected $expensesRepo;

    public function __construct(ExpensesManagementRepository $expensesRepo)
    {
        $this->repo = $expensesRepo;
    }

    public function ExpensesManagementProcess(Request $request)
    {

       $Validation = $this->validate($request, [
            'Category' => 'Required',
            'expenseId'=> Rule::requiredif($request->Category == 'Update').'|'.Rule::requiredif($request->Category == 'Delete'),
            'expenseCategory' => Rule::requiredif($request->Category == 'Register'),
            'expenseAmount' => Rule::requiredif($request->Category == 'Register'),
            'expenseEntryDate' => Rule::requiredif($request->Category == 'Register').'|date_format:Y-m-d',
        ]);

        $category = $request->Category;

        if($category == 'Get'){
            $getResponse = $this->repo->getExpenses();
            return $getResponse;
        }
        if ($category == 'Register') {
            $registerResponse = $this->repo->registerExpense($request->all());
            return $registerResponse;
        }

        if ($category == 'Update') {
            $updateResponse = $this->repo->updateExpense($request->all());
            return $updateResponse;
        }

        if ($category == 'Delete') {
            $deleteResponse = $this->repo->deleteExpense($request->all());
            return $deleteResponse;
        }


    }
}
