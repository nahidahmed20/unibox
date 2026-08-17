<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUs::first(); 
        return view('backend.about_us.index', compact('about'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image1' => 'nullable|image|max:2048',
            'image2' => 'nullable|image|max:2048',
        ]);

        $about = AboutUs::first() ?? new AboutUs();
        
        $about->title = $request->title;
        $about->subtitle = $request->subtitle;
        $about->description = $request->description;
        $about->video_url = $request->video_url;
        $about->btn_text = $request->btn_text;
        $about->btn_url = $request->btn_url;
        $about->experience_years = $request->experience_years;

        // Image 1
        if ($request->hasFile('image1')) {
            if ($about->image1 && file_exists(public_path($about->image1))) {
                unlink(public_path($about->image1));
            }
            $file = $request->file('image1');
            $filename = time() . '_1_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/about'), $filename);
            $about->image1 = 'uploads/about/' . $filename;
        }

        // Image 2
        if ($request->hasFile('image2')) {
            if ($about->image2 && file_exists(public_path($about->image2))) {
                unlink(public_path($about->image2));
            }
            $file = $request->file('image2');
            $filename = time() . '_2_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/about'), $filename);
            $about->image2 = 'uploads/about/' . $filename;
        }
        
        $about->save();

        return back()->with('success', 'About Us Section Updated Successfully!');
    }
}