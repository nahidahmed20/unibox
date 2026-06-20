<?php

namespace App\Http\Controllers\backend;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ExpenseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view brand', only: ['index']),
            new Middleware('permission:create brand', only: ['create']),
            new Middleware('permission:edit brand', only: ['edit']),
            new Middleware('permission:destroy brand', only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Expense::with('category')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category?->name ?? '—';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '';
                    // Edit Button
                    if (auth()->user()->can('expense-edit')) {
                        $buttons .= '
                            <button type="button" class="btn btn-icon btn-soft-primary btn-edit"
                                data-id="' . $row->id . '" title="Edit Expense Category">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    // Delete Button
                    if (auth()->user()->can('expense-delete')) {
                        $buttons .= '
                            <form class="delete-form d-inline" action="' . route('expenses.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button
                                    type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Expense Category">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '
                        <div class="d-flex align-items-center justify-content-center gap-2">' . $buttons . ' </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $categories = ExpenseCategory::latest()->get();

        return view('backend.expense.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
        ]);

        $expense = new Expense();
        $expense->name = $request->name;
        $expense->description = $request->description;
        $expense->amount = $request->amount;
        $expense->date = $request->date;
        $expense->expense_category_id = $request->expense_category_id; 
        $expense->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Expense created successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = Expense::find($id);

        return response()->json(['expense' => $expense]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = Expense::find($id);

        return response()->json(['expense' => $expense]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
        ]);

        $expense = Expense::findOrFail($id);

        $expense->name = $request->name;
        $expense->description = $request->description;
        $expense->amount = $request->amount;
        $expense->date = $request->date;
        $expense->expense_category_id = $request->expense_category_id; 
        $expense->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Expense updated successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::find($id);
        $expense->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Expense has been deleted successfully!',
        ]);

    }
}
