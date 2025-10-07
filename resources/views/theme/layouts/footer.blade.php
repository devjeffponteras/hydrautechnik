@php
    $contents = Setting::getFooter()->contents;

    $socmed = \App\Models\MediaAccounts::all();

    $socmedHTML = '<div class="mt-4 clearfix">';
    	foreach($socmed as $sm){
    		$socmedHTML .= '
    			<a href="'.$sm->media_account.'" class="social-icon si-small si-rounded si-colored si-'.$sm->name.'" title="'.$sm->name.'" target="_blank">
	                <i class="icon-'.$sm->name.'"></i>
	                <i class="icon-'.$sm->name.'"></i>
	            </a>
    		';
    	}

    $socmedHTML .= '</div>';


    $keywords   = ['{Social Media Icons}'];
    $variables  = [$socmedHTML];

    $footerContents = str_replace($keywords,$variables,$contents);
@endphp


{!! $footerContents !!}

<!-- Footer
============================================= -->
<footer id="footer" class="page-section dark border-0 p-0 clearfix" style="background-color: #252525 !important;">
	<div class="container clearfix">
		<!-- Footer Widgets
		============================================= -->
		<div class="footer-widgets-wrap clearfix" style="padding: 80px 0">

			<div class="row col-mb-50">
				<div class="col-lg-3">
					<a href="index.html">
					    <img src="{{ asset('/theme/addons/images/logos/logo-footer-w.png') }}" alt="logo" style="max-height: 240px;">
					</a>
				</div>

				<div class="col-lg-9">

					<div class="row col-mb-50 align-items-start">
						<div class="col-sm-6 col-md-4">
							<div class="widget">
								<h4>SERVICES</h4>
								<div class="footer-content">
									<small><a href="#">Repair of Hydraulics</a></small>
									<br>
									<small><a href="#">Lubrication Components</a></small>
									<br>
									<small><a href="#">Troubleshooting of Hydraulic</a></small>
									<br>
									<small><a href="#">Lubrication & Hydraulics</a></small>
									<br>
									<small><a href="#">Design & Fabrication</a></small>
									<br>
									<small><a href="#">Failure & Damage Analysis</a></small>
									<br>
									<small><a href="#">Trainings & Seminar</a></small>
									<br>
									<small><a href="#">System Flushing</a></small>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-4">
							<div class="widget">
								<h4>Contact us</h4>
								<div class="footer-content">
									<address class="mb-0">
										<strong>MANILA Address:</strong><br>
										<small>Unit 603 Venture Building Madrigal Business Park Ayala Alabang Muntinlupa City 1780 Philippines</small>
										<br />
									</address>
									<br />
									<span title="emails"><strong>Email us:</strong></span>
									<br />
									<small>rolanropal@hydrautechnik.com</small>
									<small>sales@hydrautechnik.com</small>
									<small>jmjuanillo@hydrautechnik.com</small>
									<small>info@hydrautechnik.com</small>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-4">
							<div class="widget">
								<h4>Social</h4>
								<a href="#" class="social-icon bg-transparent si-small si-light si-facebook">
									<i class="icon-facebook"></i>
									<i class="icon-facebook"></i>
								</a>
								<a href="#" class="social-icon bg-transparent si-small si-light si-twitter">
									<i class="icon-twitter"></i>
									<i class="icon-twitter"></i>
								</a>
								<a href="#" class="social-icon bg-transparent si-small si-light si-gplus">
									<i class="icon-gplus"></i>
									<i class="icon-gplus"></i>
								</a>
								<a href="#" class="social-icon bg-transparent si-small si-light si-instagram">
									<i class="icon-instagram"></i>
									<i class="icon-instagram"></i>
								</a>
								<a href="#" class="social-icon bg-transparent si-small si-light si-dribbble">
									<i class="icon-dribbble"></i>
									<i class="icon-dribbble"></i>
								</a>

								<a href="#" class="social-icon bg-transparent si-small si-light si-vimeo">
									<i class="icon-vimeo"></i>
									<i class="icon-vimeo"></i>
								</a>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>

	<!-- Copyrights
	============================================= -->
	<div id="copyrights" style="background-color: #111;">
		<div class="container clearfix">

			<div class="w-100 text-center">
				<p class="mb-3">Copyrights &copy;2025 Hydrautechnik, Inc.</p>
				<div class="copyright-links"><a href="#">Terms of Use</a> / <a href="#">Privacy Policy</a></div>
			</div>

		</div>
	</div><!-- #copyrights end -->
</footer><!-- #footer end -->


<!-- Subscribe Form modal
============================================= -->

<div class="modal1 mfp-hide" id="modal-subscribe">
	<div class="card mx-auto" style="max-width: 540px;">
		<div class="card-body" style="background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.3)), url('images/misc/subscribe.jpeg') no-repeat center center / cover; padding: 60px 50px; border: 12px solid #FFF">
			<div class="d-flex justify-content-between">
				<h2 class="card-title text-white font-body">Subscribe to our Newsletter!</h2>
			</div>
			<p class="text-light">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum nisi beatae temporibus nobis optio eos?</p>

			<div class="subscribe-widget" data-loader="button">

				<div class="widget-subscribe-form-result"></div>

				<form action="{{route('mailing-list.front.subscribe')}}" role="form" method="post" class="mb-0">
					@csrf
					<label for="subscriber_name" class="text-light">Name <span>*</span></label>
					<input type="text" name="name" id="subscriber_name" class="form-control required not-dark" placeholder="your name" required>

					<label for="subscriber_email" class="text-light">Email Address <span>*</span></label>
					<input type="email" name="email" id="subscriber_email" class="form-control required not-dark" placeholder="name@email.com" required>

					<button class="btn rounded btn-danger py-2 mt-3 w-100 text-uppercase ls1 fw-semibold" type="submit">Subscribe</button>
				</form>

			</div>
		</div>
	</div>
</div>
<!-- Subscribe form end modal -->