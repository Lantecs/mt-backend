<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function educExpenses()
    {
        $user_id = auth()->user()->id;
        $category = 'Education';

        $educ = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('category', $category)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['educ' => $educ]);
    }

    public function entExpenses()
    {
        $user_id = auth()->user()->id;
        $category = 'Entertainment';

        $ent = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('category', $category)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['ent' => $ent]);
    }

    public function foodExpenses()
    {
        $user_id = auth()->user()->id;
        $category = 'Food';

        $food = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('category', $category)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['food' => $food]);
    }

    public function healthExpenses()
    {
        $user_id = auth()->user()->id;
        $category = 'Health';

        $health = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('category', $category)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['health' => $health]);
    }

    public function miscExpenses()
    {
        $user_id = auth()->user()->id;
        $category = 'Miscellaneous';

        $misc = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('category', $category)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['misc' => $misc]);
    }

    public function shopExpenses()
    {
        $user_id = auth()->user()->id;
        $category = 'Shopping';

        $shop = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('category', $category)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['shop' => $shop]);
    }

    public function transExpenses()
    {
        $user_id = auth()->user()->id;
        $category = 'Transportation';

        $trans = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('category', $category)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['trans' => $trans]);
    }

    public function utilExpenses()
    {
        $user_id = auth()->user()->id;
        $category = 'Utilities';

        $util = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('category', $category)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('amount');

        return response()->json(['util' => $util]);
    }

    public function barChart()
    {

        $user_id = auth()->user()->id;

        $monday = Carbon::now()->startOfWeek();
        $tuesday = $monday->copy()->addDay();
        $wednesday = $tuesday->copy()->addDay();
        $thursday = $wednesday->copy()->addDay();
        $friday = $thursday->copy()->addDay();
        $saturday = $friday->copy()->addDay();
        $sunday = $saturday->copy()->addDay();

        $mondayIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->where('date', $monday)
            ->sum('amount');

        $tuesdayIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->where('date', $tuesday)
            ->sum('amount');

        $wednesdayIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->where('date', $wednesday)
            ->sum('amount');

        $thurdsayIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->where('date', $thursday)
            ->sum('amount');

        $fridayIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->where('date', $friday)
            ->sum('amount');

        $saturdayIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->where('date', $saturday)
            ->sum('amount');

        $sundayIncome = DB::table('user_incomes')
            ->where('user_id', $user_id)
            ->where('date', $sunday)
            ->sum('amount');

        $mondayExpenses = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('date', $monday)
            ->sum('amount');

        $tuesdayExpenses = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('date', $tuesday)
            ->sum('amount');

        $wednesdayExpenses = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('date', $wednesday)
            ->sum('amount');

        $thurdsayExpenses = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('date', $thursday)
            ->sum('amount');

        $fridayExpenses = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('date', $friday)
            ->sum('amount');

        $saturdayExpenses = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('date', $saturday)
            ->sum('amount');

        $sundayExpenses = DB::table('user_expenses')
            ->where('user_id', $user_id)
            ->where('date', $sunday)
            ->sum('amount');


        $mondayBudget = DB::table('user_budgets')
            ->where('user_id', $user_id)
            ->where('date', $monday)
            ->sum('amount');

        $tuesdayBudget = DB::table('user_budgets')
            ->where('user_id', $user_id)
            ->where('date', $tuesday)
            ->sum('amount');

        $wednesdayBudget = DB::table('user_budgets')
            ->where('user_id', $user_id)
            ->where('date', $wednesday)
            ->sum('amount');

        $thursdayBudget = DB::table('user_budgets')
            ->where('user_id', $user_id)
            ->where('date', $thursday)
            ->sum('amount');

        $fridayBudget = DB::table('user_budgets')
            ->where('user_id', $user_id)
            ->where('date', $friday)
            ->sum('amount');

        $saturdayBudget = DB::table('user_budgets')
            ->where('user_id', $user_id)
            ->where('date', $saturday)
            ->sum('amount');

        $sundayBudget = DB::table('user_budgets')
            ->where('user_id', $user_id)
            ->where('date', $sunday)
            ->sum('amount');


        $data = [
            'mondayIncome' => $mondayIncome,
            'mondayExpenses' => $mondayExpenses,
            'mondayBudget' => $mondayBudget,

            'tuesdayIncome' => $tuesdayIncome,
            'tuesdayExpenses' => $tuesdayExpenses,
            'tuesdayBudget' => $tuesdayBudget,

            'wednesdayIncome' => $wednesdayIncome,
            'wednesdayExpenses' => $wednesdayExpenses,
            'wednesdayBudget' => $wednesdayBudget,

            'thurdsayIncome' => $thurdsayIncome,
            'thurdsayExpenses' => $thurdsayExpenses,
            'thursdayBudget' => $thursdayBudget,

            'fridayIncome' => $fridayIncome,
            'fridayExpenses' => $fridayExpenses,
            'fridayBudget' => $fridayBudget,

            'saturdayIncome' => $saturdayIncome,
            'saturdayExpenses' => $saturdayExpenses,
            'saturdayBudget' => $saturdayBudget,

            'sundayIncome' => $sundayIncome,
            'sundayExpenses' => $sundayExpenses,
            'sundayBudget' => $sundayBudget,
        ];

        return response()->json($data);
    }


}
