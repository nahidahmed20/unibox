<?php

namespace App\Http\Controllers\backend;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view member', only: ['index']),
            new Middleware('permission:create member', only: ['create']),
            new Middleware('permission:member-edit', only: ['edit']),
            new Middleware('permission:member-delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $members = Member::latest();
            return DataTables::of($members)
            ->addIndexColumn()
                ->addColumn('image', function($row){
                    if($row->image){
                        return '
                        <img src="'.asset($row->image).'"
                            width="60"
                            height="60"
                            style="object-fit:cover;border-radius:8px;"
                        >';
                    }
                    return '-';
                })
                ->addColumn('status', function($row){
                    return $row->status == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function($row){
                    $buttons = '';
                    $buttons .= '
                    <button type="button"
                        class="btn btn-icon btn-soft-info show-btn"
                        data-id="'.$row->id.'"
                        title="View Member">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                    ';
                    // Edit Button
                    if(auth()->user()->can('member-edit')){
                        $buttons .= '
                        <button type="button"
                            class="btn btn-icon btn-soft-primary btn-edit"
                            data-id="'.$row->id.'"
                            title="Edit Member">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        ';
                    }
                    // Delete Button
                    if(auth()->user()->can('member-delete')){
                        $buttons .= '
                        <form action="'.route('members.destroy',$row->id).'"
                            method="POST"
                            class="delete-form d-inline">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="button"
                                class="btn btn-icon btn-soft-danger btn-delete"
                                title="Delete Member">
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
                ->rawColumns(['image','status','action'])
                ->make(true);
        }

        return view('backend.member.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'code_number'   => 'required|string|max:100|unique:members,code_number',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:255',
            'gender'        => 'required|string',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|string',
            'occupation'    => 'nullable|string',
            'nationality'   => 'nullable|string',
            'religion'      => 'nullable|string',
            'education'     => 'nullable|string',
            'status'        => 'required',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        $imagePath = null;
        if($request->hasFile('image')){
            $imageName = time().'_'.uniqid().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/members'),
                $imageName
            );
            $imagePath = 'uploads/members/'.$imageName;
        }

        Member::create([
            'name'          =>$validated['name'],
            'code_number'   =>$validated['code_number'],
            'email'         =>$validated['email'],
            'phone'         =>$validated['phone'],
            'address'       =>$validated['address'],
            'gender'        =>$validated['gender'],
            'date_of_birth' =>$validated['date_of_birth'],
            'marital_status'=>$validated['marital_status'],
            'occupation'    =>$validated['occupation'] ?? null,
            'nationality'   =>$validated['nationality'] ?? null,
            'religion'      =>$validated['religion'] ?? null,
            'education'     =>$validated['education'] ?? null,
            'status'        =>$validated['status'],
            'image'         =>$imagePath,
        ]);

        return response()->json([
            'status'=>'success',
            'message'=>'Member created successfully'
        ]);

    }

    public function edit($id)
    {

        $member = Member::findOrFail($id);
        return response()->json($member);

    }

    public function update(Request $request,$id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'name'=>'required|string|max:255',
            'code_number'=>'required|string|max:100|unique:members,code_number,'.$id,
            'email'=>'required|email|max:255',
            'phone'=>'required|string|max:20',
            'address'=>'required|string|max:255',
            'gender'=>'required|string',
            'date_of_birth'=>'required|date',
            'marital_status'=>'required|string',
            'occupation'=>'nullable|string',
            'nationality'=>'nullable|string',
            'religion'=>'nullable|string',
            'education'=>'nullable|string',
            'status'=>'required',
            'image'=>'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $member->image;
        if($request->hasFile('image')){
            if($member->image && file_exists(public_path($member->image))){
                unlink(public_path($member->image));
            }

            $imageName = time().'_'.uniqid().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/members'),
                $imageName
            );
            $imagePath='uploads/members/'.$imageName;
        }

        $member->update([
            'name'=>$validated['name'],
            'code_number'=>$validated['code_number'],
            'email'=>$validated['email'],
            'phone_number'=>$validated['phone'],
            'address'=>$validated['address'],
            'gender'=>$validated['gender'],
            'date_of_birth'=>$validated['date_of_birth'],
            'marital_status'=>$validated['marital_status'],
            'occupation'=>$validated['occupation'] ?? null,
            'nationality'=>$validated['nationality'] ?? null,
            'religion'=>$validated['religion'] ?? null,
            'education'=>$validated['education'] ?? null,
            'status'=>$validated['status'],
            'image'=>$imagePath,
        ]);

        return response()->json([
            'status'=>'success',
            'message'=>'Member updated successfully'
        ]);

    }

    public function show($id)
    {

        $member = Member::findOrFail($id);
        return response()->json($member);

    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        if($member->image && file_exists(public_path($member->image))){
            unlink(public_path($member->image));
        }
        $member->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'Member deleted successfully'
        ]);
    }
}
