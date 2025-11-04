@extends('theme.main')

@section('content')
<div class="section mt-0 pt-0" style="background-color: white;">
	<div class="container-fluid px-4 mx-4">

		<div class="d-flex">

			<div class="col-2">
				<div class="side-panel-wrap">

					<div class="widget">

						<div class="card shadow side-panel-nav d-flex align-items-center mb-2">
							<h5 class="mt-2 mb-0 pb-2">Projects</h5>
						</div>

						<nav class="nav-tree mb-0 mt-2 card shadow p-3" style="min-height: 160px;">
							@php
								// Only show published projects on the public-facing projects index
								$visibleProjects = collect($projects ?? [])->filter(function($p){
									return strtoupper($p->status ?? '') === 'PUBLISHED';
								});
							@endphp

							@if($visibleProjects->count())
								<ul>
									@foreach($visibleProjects->groupBy(function($p){ return $p->category ?? 'OTHER'; }) as $cat => $items)
										@if($items->count())
										<li><a href="#">{{ ucfirst(strtolower($cat)) }} Projects</a>
											<ul>
												@foreach($items as $p)
													<li><a href="{{ url('/projects/'.$p->id) }}">{{ $p->name }}</a></li>
												@endforeach
											</ul>
										</li>
										@endif
									@endforeach
								</ul>
							@else
								<div class="text-muted">No projects available yet.</div>
							@endif
						</nav>

					</div>

				</div>
			</div>
			<div class="col-10" style="padding: 0px 7%;">

				<div class="row align-items-center">
					@if($visibleProjects->count())
						@foreach($visibleProjects as $project)
							<div class="col-12">
								<div class="feature-box fbox-effect fbox-xl {{ $loop->iteration % 2 == 0 ? 'flex-row-reverse' : '' }} d-flex align-items-center">
									<div class="fbox-icon {{ $loop->iteration % 2 == 0 ? 'ms-3' : 'me-3' }}" style="width: 400px; height: 100%;">
										<a href="#project-{{ $project->id }}">
											@if(!empty($project->image))
												<img src="{{ asset($project->image) }}" alt="{{ $project->name }}" class="bg-transparent rounded-0" style="width:100%; height:100%; object-fit:cover;">
											@else
												<img src="{{ asset('images/products/prd1.jpg') }}" alt="{{ $project->name }}" class="bg-transparent rounded-0" style="width:100%; height:100%; object-fit:cover;">
											@endif
										</a>
									</div>
									<div class="fbox-content">
										<h2 id="project-{{ $project->id }}">{{ $project->name }}</h2>
										<p>{!! \Illuminate\Support\Str::limit($project->description ?? '', 400) !!}</p>
									</div>
								</div>
							</div>
							@if(!$loop->last)
								<div class="line my-5"></div>
							@endif
						@endforeach
					@else
						<div class="col-12 text-center">
							<p class="text-muted">No projects to display at the moment.</p>
						</div>
					@endif
				</div>

			</div>

		</div>

	</div>
</div>
@endsection
