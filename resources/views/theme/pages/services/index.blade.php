@extends('theme.main')

@section('pagecss')
<style>
/* Pagination tweaks scoped to this page for a compact, theme-friendly pager */
.pagination {
	display: inline-flex;
	padding-left: 0;
	margin: 0;
	list-style: none;
	border-radius: .25rem;
}
.pagination .page-item { margin: 0 .18rem; }
.pagination .page-link {
	color: #0b2e4a;
	background: #fff;
	border: 1px solid #e9ecef;
	padding: .38rem .62rem;
	font-size: .95rem;
	border-radius: .35rem;
}
.pagination .page-link:hover {
	background: #f1f5f8;
	color: #0b2e4a;
}
.pagination .page-item.active .page-link {
	background-color: #163a5b;
	border-color: #163a5b;
	color: #fff;
}
.pagination .page-item.disabled .page-link {
	color: #6c757d;
	pointer-events: none;
	background: transparent;
	border-color: transparent;
}

/* Keep the pager compact on small screens */
@media (max-width: 576px) {
	.pagination .page-link { padding: .28rem .48rem; font-size: .88rem; }
}
/* Simple enter animation for service rows */
.animate-item { opacity: 0; transform: translateY(18px); transition: opacity .6s ease-out, transform .6s ease-out; will-change: opacity, transform; }
.animate-item.show { opacity: 1; transform: translateY(0); }
.animate-item.show-left { opacity: 1; transform: translateX(0); }
.animate-item.show-right { opacity: 1; transform: translateX(0); }
.hidden-left { transform: translateX(-28px); }
.hidden-right { transform: translateX(28px); }
</style>
@endsection

@section('pagejs')
<script>
// Intersection observer to add .show class when elements enter viewport
document.addEventListener('DOMContentLoaded', function () {
	const items = document.querySelectorAll('.animate-item');
	if (!items || items.length === 0) return;

	const observer = new IntersectionObserver((entries, obs) => {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				const el = entry.target;
				// If element contains .flex-row-reverse or similar we can choose direction
				const reverse = el.querySelector('.feature-box')?.classList.contains('flex-row-reverse');
				el.classList.add('show');
				if (reverse) el.classList.add('show-right'); else el.classList.add('show-left');
				obs.unobserve(el);
			}
		});
	}, { threshold: 0.12 });

	items.forEach(i => observer.observe(i));
});
</script>
@endsection

@section('content')
<div class="section mt-0 pt-0" style="background-color: white;">
	<div class="container-fluid px-sm-4 mx-sm-4">

		<div class="d-flex flex-column flex-md-row">

			<div class="col col-md-2">
				<div class="side-panel-wrap">

					<div class="widget">

						<div class="card shadow side-panel-nav d-flex align-items-center mb-2">
							<h5 class="mt-2 mb-0 pb-2">Our Capabilities</h5>
						</div>

						<nav class="nav-tree mb-0 mt-2 card shadow p-3" style="min-height: 160px;">
							@if($allServices->count())
								<ul>
									<li><a href="#">Services</a>
										<ul>
											@foreach($allServices as $s)
												<li><a href="{{ route('company-capabilities.show', $s->id) }}">{{ $s->name }}</a></li>
											@endforeach
										</ul>
									</li>
								</ul>
							@else
								<div class="text-muted">No services available yet.</div>
							@endif

							<!-- Projects (dynamic from DB) -->
							@php
							use App\Models\Project;
							use Illuminate\Support\Facades\Schema;
							use Illuminate\Support\Facades\Route;

							$projectsByCategory = collect();
							if (Schema::hasTable('projects')) {
								// Only include published projects in public side panels
								$projectsByCategory = Project::where('status', 'PUBLISHED')->orderByDesc('created_at')->get()->groupBy(function($p){ return $p->category ?? 'OTHER'; });
							}
							@endphp

							<ul class="mt-2">
								<li><a href="#">Projects</a>
									<ul>
										<li><a href="#">Completed Projects</a>
											<ul>
												@forelse($projectsByCategory['COMPLETED'] ?? [] as $p)
													<li><a href="{{ url('/projects/'.$p->id) }}">{{ $p->name }}</a></li>
												@empty
													<li><a href="#">No completed projects</a></li>
												@endforelse
											</ul>
										</li>
										<li><a href="#">On Going Projects</a>
											<ul>
												@forelse($projectsByCategory['ONGOING'] ?? [] as $p)
													<li><a href="{{ url('/projects/'.$p->id) }}">{{ $p->name }}</a></li>
												@empty
													<li><a href="#">No ongoing projects</a></li>
												@endforelse
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="#">Other Projects</a>
									<ul>
										@forelse($projectsByCategory['OTHER'] ?? [] as $p)
											<li><a href="{{ url('/projects/'.$p->id) }}">{{ $p->name }}</a></li>
										@empty
											<li><a href="#">No other projects</a></li>
										@endforelse
									</ul>
								</li>
							</ul>
						</nav>

					</div>

				</div>
			</div>
			<div class="col col-md-10" style="padding: 0px 7%;">

				@php
					// Provide contents from CMS page if available
					$contents = $page->contents ?? '';
				@endphp

				<div class="mt-4 mb-4">
					{!! $contents !!}
				</div>

				<div class="row align-items-center">
					@if($services->count())
						@foreach($services as $service)
							<div class="col-12 animate-item">
								<div class="feature-box fbox-effect fbox-xl {{ $loop->iteration % 2 == 0 ? 'flex-row-reverse' : '' }} d-flex align-items-center">
									<div class="fbox-icon {{ $loop->iteration % 2 == 0 ? 'ms-3' : 'me-3' }}" style="width: 400px; height: 100%;">
										<a href="#service-{{ $service->id }}">
											@if(!empty($service->image))
												<img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="bg-transparent rounded-0" style="width:100%; height:100%; object-fit:cover;">
											@else
												<img src="{{ asset('images/products/prd1.jpg') }}" alt="{{ $service->name }}" class="bg-transparent rounded-0" style="width:100%; height:100%; object-fit:cover;">
											@endif
										</a>
									</div>
									<div class="fbox-content">
										<h2 id="service-{{ $service->id }}">{{ $service->name }}</h2>
										<p>{!! \Illuminate\Support\Str::limit($service->description ?? '', 400) !!}</p>
									</div>
								</div>
							</div>
							@if(!$loop->last)
								<div class="line my-5"></div>
							@endif
						@endforeach
					@else
						<div class="col-12 text-center">
							<p class="text-muted">No services to display at the moment.</p>
						</div>
					@endif
				</div>

					{{-- Simple pagination links for services - only show if more than 5 items --}}
					@if($services->total() > 5)
						<div class="row">
							<div class="col-12 d-flex justify-content-center mt-4">
								{!! $services->links('pagination::simple-bootstrap-4') !!}
							</div>
						</div>
					@endif

				<!-- shop like -->
				<!-- <div id="oc-posts" class="owl-carousel posts-carousel carousel-widget posts-md" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-lg="4">

					<div class="oc-item card side-panel-nav p-2 rounded shadow-sm">
						<div class="entry">
							<div class="entry-image">
								<div class="fslider" data-arrows="false" data-lightbox="gallery">
									<div class="flexslider">
										<div class="slider-wrap">
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd3.jpg" alt="Standard Post with Gallery"></a></div>
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd1.jpg" alt="Standard Post with Gallery"></a></div>
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd2.jpg" alt="Standard Post with Gallery"></a></div>
										</div>
									</div>
								</div>
							</div>
							<div class="entry-title title-xs text-transform-none px-2">
								<h3><a href="#">Piping and Fabrication Works</a></h3>
							</div>
							<div class="entry-content px-2 pb-2">
								<small>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione, voluptatem, dolorem animi nisi autem</small>
							</div>
						</div>
					</div>

					<div class="oc-item card side-panel-nav p-2 rounded shadow-sm">
						<div class="entry">
							<div class="entry-image">
								<div class="fslider" data-arrows="false" data-lightbox="gallery">
									<div class="flexslider">
										<div class="slider-wrap">
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd4.jpg" alt="Standard Post with Gallery"></a></div>
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd6.jpg" alt="Standard Post with Gallery"></a></div>
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd1.jpg" alt="Standard Post with Gallery"></a></div>
										</div>
									</div>
								</div>
							</div>
							<div class="entry-title title-xs text-transform-none px-2">
								<h3><a href="#">On Site Offline Filtration and Dewatering</a></h3>
							</div>
							<div class="entry-content px-2 pb-2">
								<small>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione, voluptatem, dolorem animi nisi autem</small>
							</div>
						</div>
					</div>

					<div class="oc-item card side-panel-nav p-2 rounded shadow-sm">
						<div class="entry">
							<div class="entry-image">
								<div class="fslider" data-arrows="false" data-lightbox="gallery">
									<div class="flexslider">
										<div class="slider-wrap">
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd6.jpg" alt="Standard Post with Gallery"></a></div>
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd2.jpg" alt="Standard Post with Gallery"></a></div>
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd4.jpg" alt="Standard Post with Gallery"></a></div>
										</div>
									</div>
								</div>
							</div>
							<div class="entry-title title-xs text-transform-none px-2">
								<h3><a href="#">Debri Filters and Preventive Maintenance</a></h3>
							</div>
							<div class="entry-content px-2 pb-2">
								<small>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione, voluptatem, dolorem animi nisi autem</small>
							</div>
						</div>
					</div>

					<div class="oc-item card side-panel-nav p-2 rounded shadow-sm">
						<div class="entry">
							<div class="entry-image">
								<div class="fslider" data-arrows="false" data-lightbox="gallery">
									<div class="flexslider">
										<div class="slider-wrap">
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd1.jpg" alt="Standard Post with Gallery"></a></div>
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd2.jpg" alt="Standard Post with Gallery"></a></div>
											<div class="slide"><a href="#" data-lightbox="gallery-item"><img src="images/products/prd3.jpg" alt="Standard Post with Gallery"></a></div>
										</div>
									</div>
								</div>
							</div>
							<div class="entry-title title-xs text-transform-none px-2">
								<h3><a href="#">Hydraulic System Flushing</a></h3>
							</div>
							<div class="entry-content px-2 pb-2">
								<small>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione, voluptatem, dolorem animi nisi autem</small>
							</div>
						</div>
					</div>

				</div> -->

				<!-- <div class="row col-mb-50 mb-0 gx-5">
					<div class="col-sm-6 col-lg-4">
						<div class="feature-box fbox-outline fbox-dark fbox-effect">
							<div class="fbox-icon">
								<a href="#"><i class="icon-params i-alt"></i></a>
							</div>
							<div class="fbox-content">
								<h3>Repair of Hydraulics / Lubrication Components</h3>
								<p>Our team specializes in the repair, refurbishment, and maintenance of hydraulic and lubrication system components, ensuring your equipment operates at peak performance with minimal downtime.</p>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-lg-4">
						<div class="feature-box fbox-outline fbox-dark fbox-effect">
							<div class="fbox-icon">
								<a href="#"><i class="icon-meter i-alt"></i></a>
							</div>
							<div class="fbox-content">
								<h3>Troubleshooting of Hydraulics & Lubrication Unit</h3>
								<p>We provide expert troubleshooting to quickly identify and resolve hydraulic and lubrication unit issues, ensuring smooth operation, reduced downtime, and improved equipment reliability.</p>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-lg-4">
						<div class="feature-box fbox-outline fbox-dark fbox-effect">
							<div class="fbox-icon">
								<a href="#"><i class="icon-battery-charging i-alt"></i></a>
							</div>
							<div class="fbox-content">
								<h3>Design & Fabrication of Customized Hydraulic Power Unit</h3>
								<p>We design and fabricate customized hydraulic power units tailored to your specific needs, delivering reliable performance, efficiency, and durability for various industrial and mobile applications.</p>
							</div>
						</div>
					</div>

				</div>

				<div class="row col-mb-50 mb-0 gx-5">
					<div class="col-sm-6 col-lg-4">
						<div class="feature-box fbox-outline fbox-dark fbox-effect">
							<div class="fbox-icon">
								<a href="#"><i class="icon-line-database i-alt"></i></a>
							</div>
							<div class="fbox-content">
								<h3>Lubrication & Hydraulic Pipes System Flushing</h3>
								<p>We perform professional flushing of lubrication and hydraulic pipe systems to remove contaminants, ensuring optimal fluid cleanliness, improved efficiency, extended component life, and reliable equipment performance.</p>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-lg-4">
						<div class="feature-box fbox-outline fbox-dark fbox-effect">
							<div class="fbox-icon">
								<a href="#"><i class="icon-line-codepen i-alt"></i></a>
							</div>
							<div class="fbox-content">
								<h3>Failure & Damage Analysis of Hydraulic System</h3>
								<p>We conduct detailed failure and damage analysis of hydraulic systems, identifying root causes to prevent recurring issues, enhance reliability, and optimize overall equipment performance.</p>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-lg-4">
						<div class="feature-box fbox-outline fbox-dark fbox-effect">
							<div class="fbox-icon">
								<a href="#"><i class="icon-line-trello i-alt"></i></a>
							</div>
							<div class="fbox-content">
								<h3>Trainings and Seminars</h3>
								<p>We provide specialized trainings and seminars on hydraulics and lubrication systems, equipping your team with essential knowledge, skills, and best practices to ensure safe, efficient, and reliable operations.</p>
							</div>
						</div>
					</div>

				</div> -->
			</div>

		</div>

	</div>
</div>
@endsection

@section('pagejs')
<script>

</script>
@endsection
