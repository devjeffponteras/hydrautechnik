@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
<div class="section mt-0 pt-4" style="background-color: #fff;">
	<div class="container px-4">

		<nav aria-label="breadcrumb" class="mb-3">
			<ol class="breadcrumb bg-transparent p-0 mb-0">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('projects') }}">Projects</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ $project->name }}</li>
			</ol>
		</nav>

						<div class="mb-4">
							<h2 class="mb-2">{{ $project->category ?? 'Uncategorized' }}</h2>
						</div>
		<div class="row">
			<div class="col-12">
				<div class="card shadow-sm border-0">
					<div class="row g-0">
						<div class="col-md-5">
							@if(!empty($project->image))
								<img src="{{ asset($project->image) }}" alt="{{ $project->name }}" class="img-fluid rounded-start" style="height:100%; object-fit:cover; width:100%; max-height:520px;">
							@else
								<img src="{{ asset('images/products/prd1.jpg') }}" alt="{{ $project->name }}" class="img-fluid rounded-start" style="height:100%; object-fit:cover; width:100%; max-height:520px;">
							@endif
						</div>
						<div class="col-md-7">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-start mb-2">
									<div>
										<h2 class="card-title">{{ $project->name }}</h2>
									</div>
									<div>
										<a href="{{ route('company-capabilities') }}" class="btn btn-sm btn-outline-secondary">Back</a>
									</div>
								</div>

								<div class="service-content mt-3">
									{!! $project->description !!}
								</div>

								@if(!empty($project->created_at))
									<p class="text-muted mt-4 mb-0 small">Published: {{ $project->created_at->format('M j, Y') }}</p>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
@endsection

@section('pagejs')
@endsection
