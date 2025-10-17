@extends('admin.layouts.app')

@section('pagetitle')
Create Equipment
@endsection

@section('pagecss')
@endsection

@section('content')
<div class="container pd-x-0">
	<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb-style1 mg-b-5">
					<li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
					<li class="breadcrumb-item"><a href="{{ route('equipments.index') }}">Equipments</a></li>
					<li class="breadcrumb-item active" aria-current="page">Create</li>
				</ol>
			</nav>
			<h4 class="mg-b-0 tx-spacing--1">Create Equipment</h4>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<form action="{{ route('equipments.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
				</div>

				<div class="form-group">
					<label for="category_id">Category</label>
					<select name="category_id" id="category_id" class="form-control">
						<option value="">-- Select Category --</option>
						@foreach($categories ?? collect() as $cat)
							<option value="{{ $cat->id }}">{{ $cat->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="image">Image</label>
					@if(isset($equipment) && $equipment->image)
						<div class="mb-2" id="current-image-block">
							<img id="current-image" src="{{ asset($equipment->image) }}" alt="Equipment Image" style="max-width: 200px; max-height: 200px; border-radius: 4px; border: 1px solid #ddd;">
						</div>
					@endif
					<input type="file" name="image" id="image" class="form-control">
					<div class="mt-2" id="preview-block" style="display:none;">
						<img id="preview-image" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 4px; border: 1px solid #ddd;" />
					</div>
				</div>
@section('pagejs')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var imageInput = document.getElementById('image');
		var previewBlock = document.getElementById('preview-block');
		var previewImage = document.getElementById('preview-image');
		var currentImageBlock = document.getElementById('current-image-block');

		if (imageInput) {
			imageInput.addEventListener('change', function(event) {
				const [file] = imageInput.files;
				if (file) {
					const reader = new FileReader();
					reader.onload = function(e) {
						previewImage.src = e.target.result;
						previewBlock.style.display = 'block';
						if (currentImageBlock) currentImageBlock.style.display = 'none';
					};
					reader.readAsDataURL(file);
				} else {
					previewBlock.style.display = 'none';
					if (currentImageBlock) currentImageBlock.style.display = '';
				}
			});
		}
	});
</script>
@endsection

				<div class="form-group">
					<label for="description">Description</label>
					<textarea name="description" id="description" class="form-control" rows="5">{{ old('description') }}</textarea>
				</div>

				<div class="form-group text-right">
					<a href="{{ route('equipments.index') }}" class="btn btn-secondary">Cancel</a>
					<button class="btn btn-primary">Create Equipment</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
