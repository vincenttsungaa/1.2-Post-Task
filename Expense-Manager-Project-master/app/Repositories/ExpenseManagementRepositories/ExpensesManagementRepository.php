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
use App\Expenses;
use Illuminate\Support\Arr;

class ExpensesManagementRepository extends Controller
{


    public function getExpenses()
    {
        $records = Expenses::get();
        return $records->toJson();
    }

    public function registerExpense($request)
    {

        $input = $request;
        $user = Expenses::create($input);

        return response()->json(['SUCCESS'],201);

    }

    public function updateExpense($request)
    {
        $expenseId = $request['expenseId'];
        $expenseCategory = !empty($request['expenseCategory']) ? $request['expenseCategory'] : '';
        $expenseAmount = !empty($request['expenseAmount']) ? $request['expenseAmount'] : '';
        $expenseEntryDate = !empty($request['expenseEntryDate']) ? $request['expenseEntryDate'] : '';

        //Checks if Expense Exists
        $categoryCount = Expenses::where('id', '=', $expenseId)
                        ->get()
                        ->count();
        if($categoryCount == 0)
        {
            return response()->json(['Expense Does Not Exist'], 400);
        }

        // Updates Expense Description
        if(!empty($request['expenseCategory']))
        {
            $dataUpdated = Expenses::where('id', '=', $expenseId)
                            ->update(['expenseCategory' => $expenseCategory]);
        }
        if(!empty($request['expenseAmount']))
        {
            $dataUpdated = Expenses::where('id', '=', $expenseId)
                            ->update(['expenseAmount' => $expenseAmount]);
        }
        if(!empty($request['expenseEntryDate']))
        {
            $dataUpdated = Expenses::where('id', '=', $expenseId)
                            ->update(['expenseEntryDate' => $expenseEntryDate]);
        }


        return response()->json(['Expense Information Updated'], 200);
    }

    public function deleteExpense($request)
    {
        $expenseId = $request['expenseId'];

        //Checks if Expense Exists
        $categoryCount = Expenses::where('id', '=', $expenseId)
                        ->get()
                        ->count();
        if($categoryCount == 0)
        {
            return response()->json(['Category Does Not Exist'], 400);
        }

        $dataDelete = Expenses::where('id', '=', $expenseId)->delete();
        
        //Convert Response to SOAP
        return response()->json(['Expense Has Been Deleted'], 200);

    }
}