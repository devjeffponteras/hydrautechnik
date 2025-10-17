@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
<div class="section mt-0 pt-0" style="background-color: white;">
	<div class="container-fluid px-4 mx-4">

		<div class="d-flex">
			
			<div class="col-2">
				<div class="side-panel-wrap">

					<div class="widget">

						<div class="card shadow side-panel-nav d-flex align-items-center mb-2">
							<h5 class="mt-2 mb-0 pb-2">Our Capabilities</h5>
						</div>

						<nav class="nav-tree mb-0 mt-2 card shadow p-3" style="min-height: 160px;">
							<ul>
								<li><a href="#">Services</a>
									<ul>
										<li><a href="#">Services 1</a></li>
										<li><a href="#">Services 2</a></li>
										<li><a href="#">Services 3</a></li>
										<li><a href="#">Services 4</a></li>
									</ul>
								</li>
								<li><a href="#">Projects</a>
									<ul>
										<li><a href="#">Completed Projects</a>
											<ul>
												<li><a href="#">1 Completed</a></li>
												<li><a href="#">2 Completed</a></li>
												<li><a href="#">3 Completed</a></li>
											</ul>
										</li>
										<li><a href="#">On Going Projects</a>
											<ul>
												<li><a href="#">1  On Going</a></li>
												<li><a href="#">2  On Going</a></li>
												<li><a href="#">3  On Going</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="#">Other Projects</a>
									<ul>
										<li><a href="#">Others 1</a></li>
										<li><a href="#">Others 2</a></li>
										<li><a href="#">Others 3</a></li>
										<li><a href="#">Others 4</a></li>
									</ul>
								</li>
							</ul>
						</nav>

					</div>
					
				</div>
			</div>
			<div class="col-10" style="padding: 0px 7%;">

				<p class="pb-2" style="opacity: .8;">
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl. Fusce eleifend ullamcorper lacinia.
				</p>

				<div class="row align-items-center">
					<div class="col-12">
						<div class="feature-box fbox-effect fbox-xl d-flex align-items-center">
							<div class="fbox-icon me-3" style="width: 400px; height: 100%;">
								<a href="#">
									<img src="images/products/prd3.jpg" alt="Feature Icon" class="bg-transparent rounded-0">
								</a>
							</div>
							<div class="fbox-content">
								<h2>Piping and Fabrication Works</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl.</p>
							</div>
						</div>
					</div>

					<div class="line my-5"></div>

					<div class="col-12">
						<div class="feature-box fbox-effect fbox-xl flex-row-reverse d-flex align-items-center">
							<div class="fbox-icon ms-3" style="width: 400px; height: 100%;">
								<a href="#">
									<img src="images/products/prd4.jpg" alt="Feature Icon" class="bg-transparent rounded-0">
								</a>
							</div>
							<div class="fbox-content">
								<h2>On Site Offline Filtration and Dewatering</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl.</p>
							</div>
						</div>
					</div>

					<div class="line my-5"></div>

					<div class="col-12">
						<div class="feature-box fbox-effect fbox-xl d-flex align-items-center">
							<div class="fbox-icon me-3" style="width: 400px; height: 100%;">
								<a href="#">
									<img src="images/products/prd2.jpg" alt="Feature Icon" class="bg-transparent rounded-0">
								</a>
							</div>
							<div class="fbox-content">
								<h2>Debri Filters and Preventive Maintenance</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl.</p>
							</div>
						</div>
					</div>

					<div class="line my-5"></div>
					<div class="col-12">
						<div class="feature-box fbox-effect fbox-xl flex-row-reverse d-flex align-items-center">
							<div class="fbox-icon ms-3" style="width: 400px; height: 100%;">
								<a href="#">
									<img src="images/products/prd1.jpg" alt="Feature Icon" class="bg-transparent rounded-0">
								</a>
							</div>
							<div class="fbox-content">
								<h2>Hydraulic System Flushing</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl.</p>
							</div>
						</div>
					</div>
				</div>

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