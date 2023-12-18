<?php

namespace App\Http\Controllers\api;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Models\VCard;
//use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DB;

class StatisticsController extends Controller
{
    public function transactionsPerCategory()
    {
        $data = Transaction::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        return response()->json($data);
    }

    public function valueOfTransactionsPerCategory()
    {
        $data = Transaction::select('category', DB::raw('sum(value) as total'))
            ->groupBy('category')
            ->get();

        return response()->json($data);
    }

    public function transactionsPerDay()
    {
        $data = Transaction::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->get();

        return response()->json($data);
    }

    public function transactionsPerMonth()
    {
        $data = Transaction::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->get();

        return response()->json($data);
    }

    public function transactionsPerYear()
    {
        $data = Transaction::select(DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as total'))
            ->groupBy('year')
            ->get();

        return response()->json($data);
    }
}