<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserExpenses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getUserExpenses()
    {
        $user_id = auth()->user()->id;
        $userExpenses = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->get();

        return response()->json(['userExpenses' => $userExpenses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function expensesSave(Request $request, $id)
    {
        $request->validate([
            'edited_type' => 'required|min:3',
            'edited_category' => 'required|in:Education,Entertainment,Food,Health,Miscellaneous,Shopping,Transportation,Utilities',
            'edited_amount' => 'required|numeric|between:0,999999.999999',
            'edited_date' => 'required|date',
        ]);

        $user_id = auth()->user()->id;

        $expenses = UserExpenses::where('user_id', $user_id)
            ->where('expenses_id', $id)
            ->first();

        if (!$expenses) {
            // Handle the case where the budget is not found, for example, redirect back
            return redirect()->back()->with('error', 'Budget not found');
        }

        $expenses->type = $request->edited_type;
        $expenses->category = $request->edited_category;
        $expenses->amount = $request->edited_amount;
        $expenses->date = Carbon::parse($request->edited_date)->format('Y-m-d H:i:s');


        $expenses->save();

        return response()->json(['success' => 'Expenses updated successfully']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function expensesAdd(Request $request)
    {
        $request->validate([
            'type' => 'required|min:3',
            'category' => 'required|in:Education,Entertainment,Food,Health,Miscellaneous,Shopping,Transportation,Utilities',
            'amount' => 'required|numeric|between:0,999999.999999',
            'date' => 'required|date',
        ]);

        $user_id = auth()->user()->id;

        $data['user_id'] = $user_id;
        $data['type'] = $request->type;
        $data['category'] = $request->category;
        $data['amount'] = $request->amount;
        $data['date'] = $request->date;

        $expenses = UserExpenses::create($data);

        return response()->json(['success' => 'Expenses added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function expensesEdit(string $id)
    {
        try {
            $user_id = auth()->user()->id;
            $expenses = UserExpenses::where('user_id', $user_id)->find($id);

            if (!$expenses) {
                return response()->json(['error' => 'Budget not found'], 404);
            }

            return response()->json(['expenses' => $expenses]);
        } catch (\Exception $e) {

            Log::error($e);

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function expensesDelete(string $id)
    {
        $user_id = auth()->user()->id;

        $deleteRow = UserExpenses::where('user_id', $user_id)
            ->where('expenses_id', $id)
            ->delete();

        // Optionally, you can return a response to indicate success or failure
        return response()->json(['message' => 'Expenses deleted successfully']);
    }
}
