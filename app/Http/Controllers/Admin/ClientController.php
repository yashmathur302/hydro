<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('id', 'desc')->paginate(10);
        return view('admin.clients.list', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->except(['logo']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadFile($request, 'logo', 'uploads/clients');
        }

        Client::create($data);

        return redirect()->route('admin.clients.index')->with('success', 'Client created successfully.');
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.update', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->except(['logo', 'remove_images']);
        $removeImages = $request->input('remove_images', []);

        // Deferred Image Deletion Logic for Logo
        if ($request->hasFile('logo')) {
            $this->deleteFile($client->logo);
            $data['logo'] = $this->uploadFile($request, 'logo', 'uploads/clients');
        } elseif (in_array('logo', $removeImages)) {
            $this->deleteFile($client->logo);
            $data['logo'] = null;
        }

        $client->update($data);

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $this->deleteFile($client->logo);
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client deleted successfully.');
    }

    // --- Helper Methods ---
    private function uploadFile($request, $fieldName, $path)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $fileName = time() . '-' . uniqid() . '.' . $file->extension();
            $file->move(public_path($path), $fileName);
            return $path . '/' . $fileName;
        }
        return null;
    }

    private function deleteFile($filePath)
    {
        if ($filePath && file_exists(public_path($filePath))) {
            unlink(public_path($filePath));
        }
    }
}
