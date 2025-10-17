@extends('admin.layouts.app')

@section('content')
<div class="container">
            <div class="card">
                <div class="card-header">Edit Equipment</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('equipments.update', $equipment->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $equipment->name) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $equipment->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            @if($equipment->image)
                                <img id="preview" src="{{ asset($equipment->image) }}" alt="Current Image" style="max-width:200px; margin-top:10px; display:block;"/>
                            @else
                                <img id="preview" src="#" alt="Image Preview" style="display:none; max-width:200px; margin-top:10px;"/>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description', $equipment->description) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
</div>
<script>
    document.getElementById('image').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    };
</script>
@endsection
