<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Counter::orderBy('sort_order', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    return $row->icon ? '<i class="' . $row->icon . ' fa-2x text-primary"></i>' : '-';
                })
                ->addColumn('count_display', function ($row) {
                    return '<span class="fw-bold fs-5">' . $row->number . ($row->suffix ?? '') . '</span>';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button type="button" class="btn btn-icon btn-soft-primary btn-edit" data-id="' . $row->id . '"><i class="fa-regular fa-pen-to-square"></i></button>
                        <form class="delete-form d-inline" action="' . route('counters.destroy', $row->id) . '" method="POST">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                        </form>';
                })
                ->rawColumns(['icon', 'count_display', 'status', 'action'])
                ->make(true);
        }
        return view('backend.counter.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'number' => 'required|numeric'
        ]);

        $counter = new Counter();
        $counter->title = $request->title;
        $counter->number = $request->number;
        $counter->suffix = $request->suffix;
        $counter->icon = $request->icon;
        $counter->sort_order = $request->sort_order ?? 0;
        $counter->status = $request->status ?? 1;
        $counter->save();

        return response()->json(['status' => 'success', 'message' => 'Counter added successfully']);
    }

    public function edit($id)
    {
        return response()->json(Counter::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'number' => 'required|numeric'
        ]);

        $counter = Counter::findOrFail($id);
        $counter->title = $request->title;
        $counter->number = $request->number;
        $counter->suffix = $request->suffix;
        $counter->icon = $request->icon;
        $counter->sort_order = $request->sort_order ?? 0;
        $counter->status = $request->status ?? 1;
        $counter->save();

        return response()->json(['status' => 'success', 'message' => 'Counter updated successfully']);
    }

    public function destroy($id)
    {
        Counter::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Counter deleted successfully']);
    }
}