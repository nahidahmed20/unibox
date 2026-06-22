<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BlockedPhoneController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $returns = Order::select('phone', DB::raw('MAX(full_name) as name'), DB::raw('COUNT(id) as return_count'))
                ->where('status', 'return')
                ->groupBy('phone')
                ->orderByDesc('return_count')
                ->get();
            
            $blockedPhones = DB::table('blocked_phones')->pluck('phone')->toArray();

            $data = $returns->map(function($item) use ($blockedPhones) {
                $item->is_blocked = in_array($item->phone, $blockedPhones);
                return $item;
            });

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row) {
                    return $row->is_blocked 
                        ? '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">Blocked</span>' 
                        : '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">Safe</span>';
                })
                ->addColumn('action', function($row) {
                    if($row->is_blocked) {
                        return '<button class="btn btn-sm btn-light border-0 text-success shadow-none rounded-3 btn-unblock" data-phone="'.$row->phone.'"><i class="bi bi-unlock-fill me-1"></i> Unblock</button>';
                    } else {
                        return '<button class="btn btn-sm btn-light border-0 text-danger shadow-none rounded-3 btn-block" data-phone="'.$row->phone.'"><i class="bi bi-ban me-1"></i> Block</button>';
                    }
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.blocked_phones.index');
    }

    public function block(Request $request)
    {
        DB::table('blocked_phones')->updateOrInsert(['phone' => $request->phone]);
        return response()->json(['success' => true, 'message' => 'Phone number blocked successfully']);
    }

    public function unblock(Request $request)
    {
        DB::table('blocked_phones')->where('phone', $request->phone)->delete();
        return response()->json(['success' => true, 'message' => 'Phone number unblocked successfully']);
    }
}
