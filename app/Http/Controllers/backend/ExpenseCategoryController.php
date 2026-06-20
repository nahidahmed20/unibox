<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ExpenseCategoryController extends Controller
{
    /**
     * INDEX (DataTable AJAX + VIEW)
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = ExpenseCategory::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $buttons = '';
                    // Edit Button
                    if (auth()->user()->can('expense-category-edit')) {
                        $buttons .= '
                            <button type="button" class="btn btn-icon btn-soft-primary btn-edit"
                                data-id="' . $row->id . '" title="Edit Expense Category">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    // Delete Button
                    if (auth()->user()->can('expense-category-delete')) {

                        $buttons .= '
                            <form class="delete-form d-inline" action="' . route('expenses-categories.destroy', $row->id) . '" method="POST">
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

        return view('backend.expense_categories.index');
    }

    /**
     * STORE (AJAX)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        ExpenseCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Category created successfully'
        ]);
    }

    /**
     * EDIT (AJAX GET)
     */
    public function edit($id)
    {
        $category = ExpenseCategory::findOrFail($id);

        return response()->json($category);
    }

    /**
     * UPDATE (AJAX)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $category = ExpenseCategory::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Category updated successfully'
        ]);
    }

    /**
     * DELETE (AJAX)
     */
    public function destroy($id)
    {
        ExpenseCategory::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Category deleted'
        ]);
    }
}
