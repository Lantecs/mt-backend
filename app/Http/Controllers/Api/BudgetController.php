<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BudgetRequest;
use App\Models\UserBudgets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getUserBudgets()
    {
        $user_id = auth()->user()->id;
        $userBudgets = DB::table('user_budgets')
            ->where('user_id', $user_id)
            ->get();

        return response()->json(['userBudgets' => $userBudgets]);
    }

    /**
     * Display a listing of the resource.
     */
    public function getRowBudgetData(Request $request)
    {
        $user_id = auth()->user()->id;
        $userBudgets = DB::table('user_budgets')
            ->where('user_id', $user_id)
            ->get();

        return response()->json(['userBudgets' => $userBudgets]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function budgetAdd(BudgetRequest $request)
    {

        $validated = $request->validated();

        $user_id = auth()->user()->id;

        $data['user_id'] = $user_id;
        $data['budget_type'] = $request->budget_type;
        $data['category'] = $request->category;
        $data['amount'] = $request->amount;
        $data['date'] = $request->date;

        $budget = UserBudgets::create($data);

        return response()->json(['success' => 'Budget added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function budgetEdit($id)
    {
        $user_id = auth()->user()->id;

        $budget = UserBudgets::where('user_id', $user_id)->find($id);

        if (!$budget) {
            return response()->json(['error' => 'Budget not found'], 404);
        }


        return response()->json(['budget' => $budget]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function budgetSave(Request $request, $id)
    {
        $request->validate([
            'edited_budget_type' => 'required|min:3',
            'edited_category' => 'required|in:Education,Entertainment,Food,Health,Miscellaneous,Shopping,Transportation,Utilities',
            'edited_amount' => 'required|numeric|between:0,999999.999999',
            'edited_date' => 'required|date',
        ]);

        $user_id = auth()->user()->id;

        // Retrieve the budget using first() to execute the query
        $budget = UserBudgets::where('user_id', $user_id)
            ->where('budget_id', $id)
            ->first();

        if (!$budget) {
            // Handle the case where the budget is not found, for example, redirect back
            return redirect()->back()->with('error', 'Budget not found');
        }

        // Update the budget with the new data
        $budget->budget_type = $request->edited_budget_type;
        $budget->category = $request->edited_category;
        $budget->amount = $request->edited_amount;
        $budget->date = Carbon::parse($request->edited_date)->format('Y-m-d H:i:s');;


        $budget->save();

        return response()->json(['success' => 'Budget updated successfully']);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function budgetDelete($id)
    {
        $user_id = auth()->user()->id;

        $deletedRows = UserBudgets::where('user_id', $user_id)
            ->where('budget_id', $id)
            ->delete();

        // Optionally, you can return a response to indicate success or failure
        return response()->json(['message' => 'Budget deleted successfully']);
    }

}
