<?php

namespace App\Http\Controllers\backend;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Customer;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $newOrdersCount = Order::where('status', 'pending')->count();
        $customersCount = Customer::count();
        $productStock   = ProductStock::sum('quantity');

        $todayOrderSales = Order::whereDate('updated_at', today())
            ->where('status', 'completed')
            ->sum('total');

        $todayPosSales = Sale::whereDate('sale_date', today())->sum('grand_total');

        $todayTotalSales = $todayOrderSales + $todayPosSales;

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $orderSale = Order::whereDate('updated_at', $date)
                ->where('status', 'completed')->sum('total');
            $posSale = Sale::whereDate('sale_date', $date)->sum('grand_total');

            $chartLabels[] = $date->format('d M');
            $chartData[] = $orderSale + $posSale;
        }

        $yearLabels = [];
        $yearData = [];
        for ($month = 1; $month <= 12; $month++) {
            $orderSale = Order::whereYear('updated_at', today()->year)
                ->whereMonth('updated_at', $month)
                ->where('status', 'completed')
                ->sum('total');

            $posSale = Sale::whereYear('sale_date', today()->year)
                ->whereMonth('sale_date', $month)
                ->sum('grand_total');

            $yearLabels[] = Carbon::create()->month($month)->format('M');
            $yearData[] = $orderSale + $posSale;
        }

        $recentOrders = Order::latest()->take(10)->get();

        return view('dashboard', compact(
            'newOrdersCount', 
            'customersCount', 
            'productStock', 
            'todayTotalSales', 
            'chartLabels', 
            'chartData', 
            'recentOrders',
            'yearLabels',
            'yearData'
        ));
    }
}
