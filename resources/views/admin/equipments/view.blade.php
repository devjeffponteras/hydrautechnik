@extends('admin.layouts.app')

@section('content')
<div class="container">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">View Equipment</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Name:</strong></label>
                        <div>{{ $equipment->name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Category:</strong></label>
                        <div>{{ $equipment->category ? $equipment->category->name : '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Image:</strong></label>
                        @if($equipment->image)
                            <div style="max-width:300px;">
                                <img id="equipment-image" src="{{ asset($equipment->image) }}" alt="Equipment Image" style="width:100%; max-width:300px; border-radius:4px; border:1px solid #ddd; cursor: zoom-in; transition: transform 0.2s;" onclick="zoomImage(this)">
                            </div>
                        @else
                            <div style="width:300px; height:200px; background-color:#f8f9fa; border:1px solid #ddd; border-radius:4px; display:flex; align-items:center; justify-content:center; color:#6c757d; font-size:14px;">No Image</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Description:</strong></label>
                        <div>{{ $equipment->description }}</div>
                    </div>
                    <a href="{{ route('equipments.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
</div>
<script>
function zoomImage(img) {
    if (img.style.transform === 'scale(2)') {
        img.style.transform = 'scale(1)';
        img.style.cursor = 'zoom-in';
    } else {
        img.style.transform = 'scale(2)';
        img.style.cursor = 'zoom-out';
    }
}
</script>
@endsection
