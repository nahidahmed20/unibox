<?php

namespace App\Http\Controllers\backend;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function divisions()
    {
        $divisions = Location::where('type', 'division')->get();
        return view('backend.locations.divisions', compact('divisions'));
    }

    public function storeDivision(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Location::create([
            'name' => $request->name,
            'type' => 'division',
            'parent_id' => null,
        ]);

        return back()->with('success', 'Division created successfully');
    }


    public function districts()
    {
        $districts = Location::where('type', 'district')
            ->with('parent')
            ->latest()
            ->get();

        $divisions = Location::where('type', 'division')->get();

        return view('backend.locations.districts', compact('districts', 'divisions'));
    }

    public function storeDistrict(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'division_id' => 'required',
        ]);

        Location::create([
            'name' => $request->name,
            'type' => 'district',
            'parent_id' => $request->division_id,
        ]);

        return back()->with('success', 'District created successfully');
    }

    public function editDistrict($id)
    {
        $district = Location::findOrFail($id);
        $divisions = Location::where('type','division')->get();

        return view('backend.locations.edit-district', compact('district','divisions'));
    }

    public function updateDistrict(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division_id' => 'required|exists:locations,id'
        ]);

        $district = Location::findOrFail($id);
        $district->update([
            'name' => $request->name,
            'parent_id' => $request->division_id,
        ]);

        return redirect()->route('locations.districts')->with('success', 'District updated successfully');
    }

    public function destroyDistrict($id)
    {
        $district = Location::findOrFail($id);
        Location::where('parent_id', $district->id)->delete();
        $district->delete();
        return back()->with('success', 'District deleted successfully');
    }


    public function upazilas()
    {
        $upazilas = Location::where('type', 'upazila')
            ->with('parent.parent')
            ->get();

        $districts = Location::where('type', 'district')->get();

        return view('backend.locations.upazilas', compact('upazilas', 'districts'));
    }

    public function storeUpazila(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'district_id' => 'required|exists:locations,id',
        ]);

        Location::create([
            'name' => $request->name,
            'type' => 'upazila',
            'parent_id' => $request->district_id,
        ]);

        return back()->with('success', 'Upazila created successfully');
    }

    public function editUpazila($id)
    {
        $upazila = Location::findOrFail($id);
        $districts = Location::where('type','district')->get();

        return view('backend.locations.edit-upazila', compact('upazila','districts'));
    }

    public function updateUpazila(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:locations,id',
        ]);

        $upazila = Location::findOrFail($id);
        $upazila->update([
            'name' => $request->name,
            'parent_id' => $request->district_id,
        ]);

        return redirect()->route('locations.upazilas')->with('success', 'Upazila updated successfully');
    }

    public function destroyUpazila($id)
    {
        $upazila = Location::findOrFail($id);
        $upazila->delete();

        return back()->with('success', 'Upazila deleted successfully');
    }

    public function getLocations($parent_id)
    {
        $locations = Location::where('parent_id', $parent_id)->orderBy('name', 'ASC')->get();
        return response()->json($locations);
    }

}
