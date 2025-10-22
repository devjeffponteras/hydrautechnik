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
        // Base query for both Main and Other products
        $baseQuery = Product::with(['subcategory.category']);

        // Apply search filters to base query
        if ($request->search) {
            $baseQuery->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('specification', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $baseQuery->whereHas('subcategory', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->subcategory) {
            $baseQuery->where('subcategory_id', $request->subcategory);
        }

        if ($request->status) {
            $baseQuery->where('status', $request->status);
        }

        // Separate queries for Main Products (exclude tag = 2) and Other Products (tag = 2 only)
        $mainProductsQuery = clone $baseQuery;
        $otherProductsQuery = clone $baseQuery;

        // Main Products: Show all products EXCEPT those with tag = 2 (with pagination)
        $mainProducts = $mainProductsQuery->where(function($q) {
            $q->whereNull('tag')->orWhere('tag', '!=', 2);
        })->orderBy('name', 'asc')->paginate(10, ['*'], 'main_page');

        // Other Products: Show only products with tag = 2 (with pagination)
        $otherProducts = $otherProductsQuery->where('tag', 2)->orderBy('name', 'asc')->paginate(10, ['*'], 'other_page');

        $categories = ProductCategory::getAllCategories()->get();
        $subcategories = ProductSubcategory::with('category')->get();

        return view('admin.products.index', compact('mainProducts', 'otherProducts', 'categories', 'subcategories'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $subcategories = ProductSubcategory::with('category')->get();
        return view('admin.products.create', compact('categories', 'subcategories'));
    }

    public function show($id)
    {
        $product = Product::with(['subcategory.category', 'productCategory'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
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
            'status' => 'required|in:PUBLISHED,PRIVATE,DRAFT',
            'is_published' => 'nullable|boolean',
        ]);

        $data = $request->all();

        // Ensure status is set correctly based on toggle
        if ($request->has('is_published') && $request->is_published) {
            $data['status'] = 'PUBLISHED';
        } else {
            $data['status'] = 'PRIVATE';
        }

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

        // Remove the is_published field as it's not in the database
        unset($data['is_published']);

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
            'status' => 'required|in:PUBLISHED,PRIVATE,DRAFT',
            'is_published' => 'nullable|boolean',
        ]);

        $product = Product::findOrFail($id);
        $data = $request->all();

        // Ensure status is set correctly based on toggle
        if ($request->has('is_published') && $request->is_published) {
            $data['status'] = 'PUBLISHED';
        } else {
            $data['status'] = 'PRIVATE';
        }

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

        // Remove the is_published field as it's not in the database
        unset($data['is_published']);

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

    public function indexCategory()
    {
        $categories = ProductCategory::getAllCategories()->get();
        return view('admin.products.index_category', compact('categories'));
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
