<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserIncomes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getUserIncomes()
    {
        $user_id = auth()->user()->id;
        $userIncomes = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->get();

        return response()->json(['userIncomes' => $userIncomes]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function incomeAdd(Request $request)
    {
        $request->validate([
            'type' => 'required|min:3',
            'amount' => 'required|numeric|between:0,999999.999999',
            'date' => 'required|date',
        ]);

        $user_id = auth()->user()->id;

        $data['user_id'] = $user_id;
        $data['type'] = $request->type;
        $data['amount'] = $request->amount;
        $data['date'] = $request->date;

        $income = UserIncomes::create($data);

        return response()->json(['success' => 'Income added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function incomeEdit(string $id)
    {
        $user_id = auth()->user()->id;

        $income = UserIncomes::where('user_id', $user_id)->find($id);

        if (!$income) {
            return response()->json(['error' => 'Income not found'], 404);
        }

        return response()->json(['income' => $income]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function incomeSave(Request $request, string $id)
    {
        $request->validate([
            'edited_type' => 'required|min:3',
            'edited_amount' => 'required|numeric|between:0,999999.999999',
            'edited_date' => 'required|date',
        ]);

        $user_id = auth()->user()->id;

        $income = UserIncomes::where('user_id', $user_id)
            ->where('income_id', $id)
            ->first();

        if (!$income) {
            // Handle the case where the budget is not found, for example, redirect back
            return redirect()->back()->with('error', 'Income not found');
        }

        $income->type = $request->edited_type;
        $income->amount = $request->edited_amount;
        $income->date = Carbon::parse($request->edited_date)->format('Y-m-d H:i:s');

        $income->save();

        return response()->json(['success' => 'Expenses updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function incomeDelete(string $id)
    {
        $user_id = auth()->user()->id;

        $deleteRow = UserIncomes::where('user_id', $user_id)
            ->where('income_id', $id)
            ->delete();

        // Optionally, you can return a response to indicate success or failure
        return response()->json(['message' => 'Income deleted successfully']);
    }

    public function dailyIncome()
    {
        $user_id = auth()->user()->id;
        $todaysDate = Carbon::now()->toDateString();

        $todaysIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->whereDate('date', $todaysDate)
            ->sum('amount');

        return response()->json(['todaysIncome' => $todaysIncome]);
    }

    public function dailySpending()
    {
        $user_id = auth()->user()->id;
        $todaysDate = Carbon::now()->toDateString();

        $todaysSpending = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->whereDate('date', $todaysDate)
            ->sum('amount');

        return response()->json(['todaysSpending' => $todaysSpending]);
    }

    public function dailySaving()
    {
        $user_id = auth()->user()->id;
        $todaysDate = Carbon::now()->toDateString();

        $todaysSpending = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->whereDate('date', $todaysDate)
            ->sum('amount');

        $todaysIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->whereDate('date', $todaysDate)
            ->sum('amount');

        $todaysSaving = $todaysIncome - $todaysSpending;
        $formattedSaving = number_format($todaysSaving, 2);

        return response()->json(['todaysSaving' => $formattedSaving]);
    }

    public function weeklyIncome()
    {
        $user_id = auth()->user()->id;

        $weeklyIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['weeklyIncome' => $weeklyIncome]);
    }

    public function weeklySpending()
    {
        $user_id = auth()->user()->id;

        $weeklySpending = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['weeklySpending' => $weeklySpending]);
    }

    public function weeklySaving()
    {
        $user_id = auth()->user()->id;

        $weeklySpending = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');


        $weeklyIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        $weeklySaving = $weeklyIncome - $weeklySpending;
        $formattedSaving = number_format($weeklySaving, 2);

        return response()->json(['weeklySaving' => $formattedSaving]);
    }


}
