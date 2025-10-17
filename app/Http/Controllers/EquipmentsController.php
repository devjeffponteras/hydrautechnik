<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\ProductCategory;

class EquipmentsController extends Controller
{
    public function index(Request $request)
    {
        $equipments = Equipment::paginate(15);
        return view('admin.equipments.index', compact('equipments'));
    }

    public function show($id)
    {
        $equipment = \App\Models\Equipment::with('category')->findOrFail($id);
        return view('admin.equipments.view', compact('equipment'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.equipments.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'category_id', 'description']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/equipments', $imageName);
            $data['image'] = 'storage/equipments/' . $imageName;
        }

        Equipment::create($data);
        return redirect()->route('equipments.index')->with('success', 'Equipment created successfully.');
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        $categories = ProductCategory::all();
        return view('admin.equipments.edit', compact('equipment', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'category_id', 'description']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/equipments', $imageName);
            $data['image'] = 'storage/equipments/' . $imageName;
        }

        $equipment->update($data);
        return redirect()->route('equipments.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();
        return redirect()->route('equipments.index')->with('success', 'Equipment deleted successfully.');
    }
}