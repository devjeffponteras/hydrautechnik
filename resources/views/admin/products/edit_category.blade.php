@extends('admin.layouts.app')

@section('pagetitle')
Edit Category
@endsection

@section('content')
<div class="container">
    <div class="row">
        <!-- Edit Category Form -->
        <div class="col-md-6">
            <h4 class="mb-3">Edit Product Category</h4>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('products.update_category', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control">{{ $category->description }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Update Category</button>
                        <a href="{{ route('products.create_category') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="col-md-6">
            <h4 class="mb-3">All Categories</h4>
            <div class="card">
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $cat)
                                    <tr @if($cat->id == $category->id) class="table-warning" @endif>
                                        <td>{{ $cat->id }}</td>
                                        <td>{{ $cat->name }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($cat->description, 30) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.edit_category', $cat->id) }}" class="btn btn-sm btn-primary" title="Edit Category">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <form action="{{ route('products.destroy_category', $cat->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Category">
                                                        <i data-feather="trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No categories found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

