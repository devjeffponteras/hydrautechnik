<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $projects = collect();
        $categories = [
            'COMPLETED' => 'Completed',
            'ONGOING' => 'On Going',
            'OTHER' => 'Other',
        ];

        $statuses = [
            'PUBLISHED' => 'Published',
            'PRIVATE' => 'Private',
        ];

        if (Schema::hasTable('projects')) {
            $query = Project::orderByDesc('created_at');

            // apply category filter if provided and valid
            $category = $request->get('category');
            if (!empty($category) && array_key_exists($category, $categories)) {
                $query->where('category', $category);
            }

            // apply status filter if provided and valid
            $status = $request->get('status');
            if (!empty($status) && array_key_exists($status, $statuses)) {
                $query->where('status', $status);
            }

            // apply simple text search (search in name)
            $q = $request->get('q');
            if (!empty($q)) {
                $query->where('name', 'like', '%'.trim($q).'%');
            }

            $projects = $query->get();
        }

        return view('admin.projects.index', compact('projects', 'categories', 'statuses'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('projects')) {
            return redirect()->back()->with('error', 'Projects table not found. Please run migrations.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|in:COMPLETED,ONGOING,OTHER',
            'status' => 'nullable|string|in:PUBLISHED,PRIVATE',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $imagePath = 'storage/' . $path; // to use with asset()
        }

        $project = new Project();
        $project->name = $data['name'];
        $project->description = $data['description'] ?? null;
        $project->image = $imagePath;
        $project->status = $data['status'] ?? 'PUBLISHED';
        if (!empty($data['category']) && Schema::hasColumn('projects', 'category')) {
            $project->category = $data['category'];
        }
        $project->save();

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show($id)
    {
        if (!Schema::hasTable('projects')) {
            abort(404);
        }

        $project = Project::findOrFail($id);
        return view('admin.projects.show', compact('project'));
    }

    public function edit($id)
    {
        if (!Schema::hasTable('projects')) {
            return redirect()->route('projects.index')->with('error', 'Projects table not found.');
        }

        $project = Project::findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        if (!Schema::hasTable('projects')) {
            return redirect()->back()->with('error', 'Projects table not found.');
        }

        $project = Project::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|in:COMPLETED,ONGOING,OTHER',
            'status' => 'nullable|string|in:PUBLISHED,PRIVATE',
        ]);

        if ($request->hasFile('image')) {
            // delete old image if present and in storage
            if (!empty($project->image) && Str::startsWith($project->image, 'storage/')) {
                $old = Str::after($project->image, 'storage/');
                if (Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }
            $path = $request->file('image')->store('projects', 'public');
            $project->image = 'storage/' . $path;
        }

        $project->name = $data['name'];
        $project->description = $data['description'] ?? null;
        if (!empty($data['category']) && Schema::hasColumn('projects', 'category')) {
            $project->category = $data['category'];
        }
        $project->status = $data['status'] ?? $project->status;
        $project->save();

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy($id)
    {
        if (!Schema::hasTable('projects')) {
            return redirect()->route('projects.index')->with('error', 'Projects table not found.');
        }

        $project = Project::findOrFail($id);

        // delete image if exists
        if (!empty($project->image) && Str::startsWith($project->image, 'storage/')) {
            $old = Str::after($project->image, 'storage/');
            if (Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
