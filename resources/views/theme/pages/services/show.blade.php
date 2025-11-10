@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
<div class="section mt-0 pt-4" style="background-color: #fff;">
	<div class="container px-4">

		<nav aria-label="breadcrumb" class="mb-3">
			<ol class="breadcrumb bg-transparent p-0 mb-0">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('company-capabilities') }}">Services</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
			</ol>
		</nav>

		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2 class="mb-0">Service</h2>
			<a href="{{ route('company-capabilities') }}" class="btn btn-sm btn-outline-secondary">Back</a>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="card shadow-sm border-0">
					<div class="row g-0">
						<div class="col-md-5">
							@if(!empty($service->image))
								<img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="img-fluid rounded-start" style="height:100%; object-fit:cover; width:100%; max-height:520px;">
							@else
								<img src="{{ asset('images/products/prd1.jpg') }}" alt="{{ $service->name }}" class="img-fluid rounded-start" style="height:100%; object-fit:cover; width:100%; max-height:520px;">
							@endif
						</div>
						<div class="col-md-7">
							<div class="card-body">
								<h2 class="card-title mb-3">{{ $service->name }}</h2>

								<div class="service-content mt-3">
									{!! $service->description !!}
								</div>

								@if(!empty($service->created_at))
									<p class="text-muted mt-4 mb-0 small">Published: {{ $service->created_at->format('M j, Y') }}</p>
								@endif

								<!-- Related / Other Services -->
								@php
									use App\Models\Service as ServiceModel;
									$otherServices = collect();
									if (class_exists(ServiceModel::class)) {
										$otherServices = ServiceModel::where('status', 'PUBLISHED')
											->where('id', '<>', $service->id)
											->orderBy('name', 'asc')
											->limit(4)
											->get();
									}
								@endphp

								@if($otherServices->count())
									<div class="mt-4">
										<h6 class="mb-2">Other Services</h6>
										<ul class="list-unstyled mb-0">
											@foreach($otherServices as $os)
												<li class="mb-2"><a href="{{ route('company-capabilities.show', $os->id) }}">{{ $os->name }}</a></li>
											@endforeach
										</ul>
									</div>
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
