@php
    $is_video = 0;
    if($page->album->banner_type == 'video'){
        $is_video = 1;
    }
@endphp

<section id="slider" class="slick-wrapper clearfix home-slider-banner" style="min-height: 460px !important;"><!--.include-header-->
	<div class="banner-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12" style="padding:0;">
					<div id="banner" class="home-slider slick-slider">
						@foreach ($page->album->banners as $banner)

							@if($is_video > 0)

							@else
								<div class="hero-slide dark">
									<img src="{{ $banner->image_path }}" alt="{{ $banner->title }}">
									<div class="banner-caption">
										{{ $banner->url }}
										<div class="container">
											<div class="row align-items-center">
												<div class="col-lg-12">
													<h2 class="text-center slide-content">{{ $banner->title }}</h2>
													<p class="d-none d-sm-block text-center mx-wd-750-f mx-auto slide-content2" data-delay="200">{{ $banner->description }}</p>

													@if($banner->url && $banner->button_text)
														<div class="d-flex justify-content-center mt-5" data-delay="400">
															<a href="{{ $banner->url }}" class="button button-large button-border">{{ $banner->button_text }}</a>
					                                    </div>
					                                @endif
												</div>
											</div>
										</div>
									</div>
								</div>
							@endif
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</section>