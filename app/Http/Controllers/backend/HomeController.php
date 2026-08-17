<?php

namespace App\Http\Controllers\backend;

use App\Models\Contact;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home.index');
    }

    public function contactUsData(Request $request)
    {
        if ($request->ajax()) {
            $data = Contact::latest(); 
            
            return DataTables::of($data)
                ->addIndexColumn() 
                ->editColumn('message', function($row) {
                    return Str::limit($row->message, 40);
                })
                ->editColumn('subject', function($row) {
                    return $row->subject ?? '—';
                })
                ->editColumn('phone', function($row) {
                    return $row->phone ?? '—';
                })
                ->addColumn('action', function($row) {
                    $btn = '<button class="btn btn-action btn-view" data-id="'.$row->id.'">
                                <i class="fa-solid fa-eye"></i>
                            </button>&nbsp;';
                    
                    $deleteUrl = route('contacts.destroy', $row->id);
                    
                    $btn .= '<form action="'.$deleteUrl.'" method="POST" class="d-inline delete-form">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="button" class="btn btn-action btn-delete-custom btn-delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>';
                            
                    return $btn;
                })
                ->rawColumns(['action']) 
                ->make(true);
        }

        return view('backend.contact.index');
    }

    public function contactShow($id)
    {
        return Contact::findOrFail($id);
    }

    public function contactDestroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Contact deleted successfully'
        ]);
    }

    public function newsletterList()
    {
        $newsletters = Newsletter::latest()->get();
 
        return view('backend.newsletter.index', compact('newsletters'));
    }

    public function newsletterDestroy($id)
    {
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Subscription deleted successfully'
        ]);
    }

}
