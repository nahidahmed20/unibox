<?php

namespace App\Http\Controllers\backend;

use App\Models\Contact;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home.index');
    }

    public function contactUsData()
    {
        $contacts = Contact::latest()->get();
 
        return view('backend.contact.index', compact('contacts'));
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
