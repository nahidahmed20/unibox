<?php

namespace App\Http\Controllers\backend;

use App\Models\Sale;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Purchase;

class ReportController extends Controller
{
    public function totalIncome(Request $request)
    {
        $queryOrders   = Order::where('status', 'completed');
        $queryReturns  = Order::where('status', 'return'); 
        $querySales    = Sale::query();
        $queryExpenses = Expense::query();

        // ========================
        // PERIOD FILTER
        // ========================
        if ($request->filled('period')) {

            switch ($request->period) {

                case 'today':
                    $date = now()->toDateString();

                    $queryOrders->whereDate('created_at', $date);
                    $queryReturns->whereDate('updated_at', $date); 
                    $querySales->whereDate('sale_date', $date);
                    $queryExpenses->whereDate('date', $date);
                    break;

                case 'month':
                    $month = now()->month;
                    $year  = now()->year;

                    $queryOrders->whereMonth('created_at', $month)->whereYear('created_at', $year);
                    $queryReturns->whereMonth('updated_at', $month)->whereYear('updated_at', $year);
                    $querySales->whereMonth('sale_date', $month)->whereYear('sale_date', $year);
                    $queryExpenses->whereMonth('date', $month)->whereYear('date', $year);
                    break;

                case 'year':
                    $year = now()->year;

                    $queryOrders->whereYear('created_at', $year);
                    $queryReturns->whereYear('updated_at', $year);
                    $querySales->whereYear('sale_date', $year);
                    $queryExpenses->whereYear('date', $year);
                    break;
            }
        }

        // ========================
        // CUSTOM DATE RANGE
        // ========================
        if ($request->filled('start_date') && $request->filled('end_date')) {

            $start = $request->start_date . ' 00:00:00';
            $end   = $request->end_date . ' 23:59:59';

            $queryOrders->whereBetween('created_at', [$start, $end]);
            $queryReturns->whereBetween('updated_at', [$start, $end]);
            $querySales->whereBetween('sale_date', [$start, $end]);
            $queryExpenses->whereBetween('date', [$start, $end]);
        }

        // ========================
        // CALCULATION
        // ========================
        $orderIncome   = $queryOrders->sum('total');
        $posIncome     = $querySales->sum('paid_amount');
        $totalExpenses = $queryExpenses->sum('amount');
        
        $totalReturnPenalty = $queryReturns->sum('return_charge'); 

        $totalIncome   = ($orderIncome + $posIncome) - ($totalExpenses + $totalReturnPenalty);

        // ========================
        // RESPONSE (AJAX)
        // ========================
        if ($request->ajax()) {
            return response()->json([
                'orderIncome'        => $orderIncome,
                'posIncome'          => $posIncome,
                'totalExpenses'      => $totalExpenses,
                'totalReturnPenalty' => $totalReturnPenalty, 
                'totalIncome'        => $totalIncome,
            ]);
        }

        // ========================
        // VIEW
        // ========================
        return view('backend.reports.total_income', compact(
            'orderIncome',
            'posIncome',
            'totalExpenses',
            'totalReturnPenalty',
            'totalIncome'
        ));
    }

    public function salesReport(Request $request)
    {
        $query = Sale::query();
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('sale_date', [$request->start_date, $request->end_date]);
        }
        $sales = $query->latest()->get();
        return view('backend.reports.sales', compact('sales'));
    }

    // Purchase Report
    public function purchaseReport(Request $request)
    {
        $query = Purchase::query();
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
        }
        $purchases = $query->latest()->get();
        return view('backend.reports.purchase', compact('purchases'));
    }


}
