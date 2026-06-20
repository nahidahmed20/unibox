<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Customer::latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $buttons = '';
                    $user = auth()->user();

                    // Show Button
                    if ($user->can('customer-show')) {
                        $buttons .= '
                            <button type="button"
                                class="btn btn-icon btn-soft-info showBtn"
                                data-id="' . $row->id . '"
                                title="View Customer">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        ';
                    }

                    // Edit Button
                    if ($user->can('customer-edit')) {
                        $buttons .= '
                            <a href="' . route('customers.edit', $row->id) . '"
                                class="btn btn-icon btn-soft-primary"
                                title="Edit Customer">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                        ';
                    }

                    // Delete Button
                    if ($user->can('customer-delete')) {
                        $buttons .= '
                            <form class="delete-form d-inline"
                                action="' . route('customers.destroy', $row->id) . '"
                                method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="button"
                                    class="btn btn-icon btn-soft-danger btn-delete"
                                    title="Delete Customer">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            '.$buttons.'
                        </div>
                    ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        // Summary Cards Data
        $customersCount = Customer::count();

        $activeCustomers = Customer::where('status', 1)->count();

        $wholesaleCustomers = Customer::where('type', 'wholesale')->count();

        $totalDueAmount = Customer::sum('total_due');

        return view('backend.customer.index', compact(
            'customersCount',
            'activeCustomers',
            'wholesaleCustomers',
            'totalDueAmount'
        ));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'nullable|string|max:255',
            'phone' => 'required|digits_between:10,15|unique:customers,phone',
        ]);

        try {

            $customer = DB::transaction(function () use ($request) {
                $user = null;
                if ($request->boolean('create_account')) {
                    $user = User::create([
                        'name'     => $request->name,
                        'email'    => $request->email,
                        'password' => Hash::make('12345678'),
                        'is_admin' => 0,
                    ]);
                }

                return Customer::create([
                    'user_id'         => $user?->id,
                    'customer_code'   => 'CUS-' . now()->format('YmdHis'),
                    'name'            => $request->name,
                    'phone'           => $request->phone,
                    'alternate_phone' => $request->alternate_phone,
                    'address'         => $request->address,
                    'city'            => $request->city,
                    'state'           => $request->state,
                    'postal_code'     => $request->postal_code,
                    'country'         => $request->country,
                    'opening_balance' => $request->opening_balance ?? 0,
                    'total_due'       => $request->total_due ?? 0,
                    'type'            => $request->boolean('create_account') ? 'regular' : 'walk-in',
                    'status'          => $request->status ?? 1,
                    'note'            => $request->note,
                ]);
            });

            return response()->json([
                'success'  => true,
                'message'  => 'Customer saved successfully!',
                'customer' => $customer
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('backend.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'nullable|string|max:255',
            'phone' => 'required|digits_between:10,15|unique:customers,phone,' . $id,
        ]);

        try {

            $updatedCustomer = DB::transaction(function () use ($request, $customer) {

                // OPTIONAL: update linked user
                if ($customer->user_id) {
                    $customer->user->update([
                        'name'  => $request->name,
                        'email' => $request->email,
                    ]);
                }

                $customer->update([

                    'name'            => $request->name,
                    'phone'           => $request->phone,
                    'alternate_phone' => $request->alternate_phone,

                    'address'         => $request->address,
                    'city'            => $request->city,
                    'state'           => $request->state,
                    'postal_code'     => $request->postal_code,
                    'country'         => $request->country,

                    'opening_balance' => $request->opening_balance ?? 0,
                    'total_due'       => $request->total_due ?? 0,

                    'type'            => $request->type ?? 'walk-in',
                    'status'          => $request->status ?? 1,

                    'note'            => $request->note,

                ]);

                return $customer;
            });

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully!',
                'customer' => $updatedCustomer
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $customer = Customer::findOrFail($id);

            DB::transaction(function () use ($customer) {

                // OPTIONAL: delete linked user (if exists)
                if ($customer->user_id && $customer->user) {
                    $customer->user->delete();
                }

                $customer->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
