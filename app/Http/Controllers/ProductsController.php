<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use App\Models\SubProduct; // Keep for backward compatibility

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['subcategory.category']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('specification', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->subcategory) {
            $query->where('subcategory_id', $request->subcategory);
        }

        $products = $query->paginate(10);
        $categories = ProductCategory::getAllCategories()->get();
        $subcategories = ProductSubcategory::with('category')->get();

        return view('admin.products.index', compact('products', 'categories', 'subcategories'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $subcategories = ProductSubcategory::with('category')->get();
        return view('admin.products.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_subcategories,id',
            'description' => 'nullable|string',
            'specification' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // If subcategory not provided but category is, set category_id directly on product
        if (empty($data['subcategory_id']) && !empty($data['category_id'])) {
            $data['subcategory_id'] = null;
            $data['category_id'] = $request->category_id;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('public/products', $imageName);
            $data['image'] = 'storage/products/' . $imageName;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with('subcategory.category')->findOrFail($id);
        $categories = ProductCategory::all();
        $subcategories = ProductSubcategory::with('category')->get();
        return view('admin.products.edit', compact('product', 'categories', 'subcategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_subcategories,id',
            'description' => 'nullable|string',
            'specification' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $data = $request->all();

        if (empty($data['subcategory_id']) && !empty($data['category_id'])) {
            $data['subcategory_id'] = null;
            $data['category_id'] = $request->category_id;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('public/products', $imageName);
            $data['image'] = 'storage/products/' . $imageName;
        } else {
            // Keep existing image if no new image uploaded
            unset($data['image']);
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete associated image if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // Subcategory Management Methods
    public function createSubcategory()
    {
        $categories = ProductCategory::getAllCategories()->get();
        $subcategories = ProductSubcategory::with('category')->get();
        return view('admin.products.create_subcategory', compact('categories', 'subcategories'));
    }

    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('public/subcategories', $imageName);
            $data['image'] = 'storage/subcategories/' . $imageName;
        }

        ProductSubcategory::create($data);

        return redirect()->route('products.create_subcategory')->with('success', 'Subcategory created successfully.');
    }

    public function editSubcategory($id)
    {
        $subcategory = ProductSubcategory::with('category')->findOrFail($id);
        $categories = ProductCategory::getAllCategories()->get();
        $subcategories = ProductSubcategory::with('category')->get();
        return view('admin.products.edit_subcategory', compact('subcategory', 'categories', 'subcategories'));
    }

    public function updateSubcategory(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $subcategory = ProductSubcategory::findOrFail($id);
        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($subcategory->image && file_exists(public_path($subcategory->image))) {
                unlink(public_path($subcategory->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('public/subcategories', $imageName);
            $data['image'] = 'storage/subcategories/' . $imageName;
        } else {
            // Keep existing image if no new image uploaded
            unset($data['image']);
        }

        $subcategory->update($data);

        return redirect()->route('products.create_subcategory')->with('success', 'Subcategory updated successfully.');
    }

    public function destroySubcategory($id)
    {
        $subcategory = ProductSubcategory::findOrFail($id);

        // Delete associated image if exists
        if ($subcategory->image && file_exists(public_path($subcategory->image))) {
            unlink(public_path($subcategory->image));
        }

        $subcategory->delete();

        return redirect()->route('products.create_subcategory')->with('success', 'Subcategory deleted successfully.');
    }
    public function createCategory()
    {
        $categories = ProductCategory::getAllCategories()->get();
        return view('admin.products.create_category', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        ProductCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('products.create_category')->with('success', 'Category created successfully.');
    }

    public function editCategory($id)
    {
    $category = ProductCategory::findOrFail($id);
    $categories = ProductCategory::all();
    return view('admin.products.edit_category', compact('category', 'categories'));
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = ProductCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('products.create_category')->with('success', 'Category updated successfully.');
    }

    public function destroyCategory($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('products.create_category')->with('success', 'Category deleted successfully.');
    }
}
