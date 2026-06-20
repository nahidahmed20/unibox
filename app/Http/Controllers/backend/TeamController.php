<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $teams = Team::query()->latest();

            return datatables()->of($teams)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image 
                        ? '<img src="' . asset($row->image) . '" style="width:50px; height:50px; object-fit:cover; border-radius:50%; border:1px solid #ddd;">' 
                        : '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 
                        ? '<span class="badge bg-success">Active</span>' 
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '';
                    
                    // Edit Button for Modal (Permission Check can be added if needed)
                    $buttons .= '
                        <button type="button" class="btn btn-icon btn-soft-primary btn-edit" data-id="' . $row->id . '" title="Edit Team Member">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    ';

                    // Delete Button
                    $buttons .= '
                        <form class="delete-form d-inline" action="' . route('teams.destroy', $row->id) . '" method="POST">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Member">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';

                    return '<div class="d-flex align-items-center justify-content-center gap-2">' . $buttons . '</div>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.team.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'facebook'    => 'nullable|url',
            'twitter'     => 'nullable|url',
            'instagram'   => 'nullable|url',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'      => 'required|boolean',
        ]);

        $team = new Team();
        $team->name        = $request->name;
        $team->designation = $request->designation;
        $team->facebook    = $request->facebook;
        $team->twitter     = $request->twitter;
        $team->instagram   = $request->instagram;
        $team->status      = $request->status;

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/teams'), $imageName);
            $team->image = 'uploads/teams/' . $imageName;
        }

        $team->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Team member added successfully'
        ]);
    }

    public function edit($id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'facebook'    => 'nullable|url',
            'twitter'     => 'nullable|url',
            'instagram'   => 'nullable|url',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'      => 'required|boolean',
        ]);

        $team = Team::findOrFail($id);
        $team->name        = $request->name;
        $team->designation = $request->designation;
        $team->facebook    = $request->facebook;
        $team->twitter     = $request->twitter;
        $team->instagram   = $request->instagram;
        $team->status      = $request->status;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($team->image && file_exists(public_path($team->image))) {
                unlink(public_path($team->image));
            }

            $image     = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/teams'), $imageName);
            $team->image = 'uploads/teams/' . $imageName;
        }

        $team->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Team member updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        if ($team->image && file_exists(public_path($team->image))) {
            unlink(public_path($team->image));
        }
        $team->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Team member deleted successfully',
        ]);
    }
}