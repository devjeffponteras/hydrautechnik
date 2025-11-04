<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServicesController extends Controller
{
    public function index(Request $request)
    {
        $services = [];
        $statuses = [
            'PUBLISHED' => 'Published',
            'PRIVATE' => 'Private',
        ];

        if (Schema::hasTable('services')) {
            $query = DB::table('services')->orderBy('id', 'desc');

            // search by name
            $q = $request->get('q');
            if (!empty($q)) {
                $query->where('name', 'like', '%'.trim($q).'%');
            }

            // filter by status
            $status = $request->get('status');
            if (!empty($status) && array_key_exists($status, $statuses)) {
                $query->where('status', $status);
            }

            $services = $query->get();
        }

        return view('admin.services.index', compact('services', 'statuses'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // accept status when present but compute a safe default below so the form still works
            'status' => 'nullable|in:PUBLISHED,PRIVATE',
            'image' => 'nullable|image|max:2048'
        ]);

        if (!Schema::hasTable('services')) {
            return redirect()->route('services.index')->with('error', 'Services table does not exist. Please create the table before saving records.');
        }

        // Determine status: prefer explicit 'status' input (matches Projects controller behavior),
        // then fall back to the is_published checkbox value (1/0). Default to PUBLISHED for safety.
        $submittedStatus = strtoupper($request->input('status', ''));
        if (!in_array($submittedStatus, ['PUBLISHED', 'PRIVATE'])) {
            $isPublishedValue = $request->input('is_published', null);
            if ($isPublishedValue !== null) {
                $submittedStatus = ((string)$isPublishedValue === '1') ? 'PUBLISHED' : 'PRIVATE';
            } else {
                $submittedStatus = 'PUBLISHED';
            }
        }

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $submittedStatus,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Handle image upload if column exists
        if (Schema::hasColumn('services', 'image') && $request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            if ($path) {
                $data['image'] = 'storage/' . $path;
            }
        }

        DB::table('services')->insert($data);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit($id)
    {
        if (!Schema::hasTable('services')) {
            return redirect()->route('services.index')->with('error', 'Services table does not exist.');
        }

        $service = DB::table('services')->find($id);

        if (!$service) {
            return redirect()->route('services.index')->with('error', 'Service not found.');
        }

        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // make status optional here too and compute a safe value below
            'status' => 'nullable|in:PUBLISHED,PRIVATE',
            'image' => 'nullable|image|max:2048'
        ]);

        if (!Schema::hasTable('services')) {
            return redirect()->route('services.index')->with('error', 'Services table does not exist.');
        }

        // Determine status: prefer explicit 'status' input (matches Projects controller behavior),
        // then fall back to the is_published checkbox value (1/0). Default to current project status if absent.
        $submittedStatus = strtoupper($request->input('status', ''));
        if (!in_array($submittedStatus, ['PUBLISHED', 'PRIVATE'])) {
            $isPublishedValue = $request->input('is_published', null);
            if ($isPublishedValue !== null) {
                $submittedStatus = ((string)$isPublishedValue === '1') ? 'PUBLISHED' : 'PRIVATE';
            } else {
                // keep existing status if no input provided
                $submittedStatus = $service->status ?? 'PUBLISHED';
            }
        }

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $submittedStatus,
            'updated_at' => now(),
        ];

        // Handle image replacement
        if (Schema::hasColumn('services', 'image') && $request->hasFile('image')) {
            $service = DB::table('services')->find($id);
            // delete old file if present
            if ($service && !empty($service->image)) {
                $old = Str::replaceFirst('storage/', '', $service->image);
                Storage::disk('public')->delete($old);
            }

            $path = $request->file('image')->store('services', 'public');
            if ($path) {
                $data['image'] = 'storage/' . $path;
            }
        }

        $updated = DB::table('services')->where('id', $id)->update($data);

        if (!$updated) {
            return redirect()->route('services.index')->with('error', 'Failed to update service.');
        }

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy($id)
    {
        if (!Schema::hasTable('services')) {
            return redirect()->route('services.index')->with('error', 'Services table does not exist.');
        }

        // delete image file if present
        if (Schema::hasColumn('services', 'image')) {
            $service = DB::table('services')->find($id);
            if ($service && !empty($service->image)) {
                $old = Str::replaceFirst('storage/', '', $service->image);
                Storage::disk('public')->delete($old);
            }
        }

        DB::table('services')->where('id', $id)->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted.');
    }
}
