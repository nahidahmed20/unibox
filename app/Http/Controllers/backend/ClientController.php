<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Client::orderBy('sort_order', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    return '<img src="'.asset($row->logo).'" style="height:40px; border-radius:4px;">';
                })
                ->addColumn('url', function ($row) {
                    return $row->url ? '<a href="'.$row->url.'" target="_blank" class="text-primary"><i class="fa fa-link"></i> Link</a>' : '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button type="button" class="btn btn-icon btn-soft-primary btn-edit" data-id="' . $row->id . '"><i class="fa-regular fa-pen-to-square"></i></button>
                        <form class="delete-form d-inline" action="' . route('clients.destroy', $row->id) . '" method="POST">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                        </form>';
                })
                ->rawColumns(['logo', 'url', 'status', 'action'])
                ->make(true);
        }
        return view('backend.client.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|max:2048',
            'name' => 'nullable|string|max:255',
            'url'  => 'nullable|url'
        ]);

        $client = new Client();
        $client->name = $request->name;
        $client->url = $request->url;
        $client->sort_order = $request->sort_order ?? 0;
        $client->status = $request->status ?? 1;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/clients'), $filename);
            $client->logo = 'uploads/clients/' . $filename;
        }
        
        $client->save();
        return response()->json(['status' => 'success', 'message' => 'Client added successfully']);
    }

    public function edit($id)
    {
        return response()->json(Client::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'name' => 'nullable|string|max:255',
            'url'  => 'nullable|url'
        ]);

        $client = Client::findOrFail($id);
        $client->name = $request->name;
        $client->url = $request->url;
        $client->sort_order = $request->sort_order ?? 0;
        $client->status = $request->status ?? 1;

        if ($request->hasFile('logo')) {
            if ($client->logo && file_exists(public_path($client->logo))) {
                unlink(public_path($client->logo));
            }
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/clients'), $filename);
            $client->logo = 'uploads/clients/' . $filename;
        }
        
        $client->save();
        return response()->json(['status' => 'success', 'message' => 'Client updated successfully']);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        if ($client->logo && file_exists(public_path($client->logo))) {
            unlink(public_path($client->logo));
        }
        $client->delete();
        return response()->json(['status' => 'success', 'message' => 'Client deleted successfully']);
    }
}