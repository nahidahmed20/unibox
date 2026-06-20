<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('backend.setting.index', compact('settings'));
    }
    public function save(Request $request)
    {
        foreach ($request->except('_token') as $key => $input) {

            $value = $input;

            if ($request->hasFile($key)) {

                $file = $request->file($key);
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/settings'), $fileName);

                $value = 'uploads/settings/' . $fileName;

                $old = Setting::where('key', $key)->first();

                if ($old && $old->value) {
                    $oldPath = public_path($old->value);

                    // 🔐 SAFE CHECK
                    if (file_exists($oldPath) && is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully!');
    }


    // Get setting
    public function get($key)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : null;
    }
}

