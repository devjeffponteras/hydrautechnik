{{-- <section id="slider" class="slick-wrapper clearfix">
	<div class="banner-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12" style="padding:0;">
					<div class="sub-banner-caption">
						<div class="container" style="position: relative;">
							<h2 class="text-center excerpt-1 text-light">{{ $page->name }}</h2>
							<div class="sub-banner-flex">
								@if(isset($breadcrumb))
					            <ol class="breadcrumb nobottommargin flex-nowrap justify-content-center">
					                @foreach($breadcrumb as $link => $url)
					                    @if($loop->last)
					                        <li class="breadcrumb-item active excerpt-1 text-light" aria-current="page">{{$link}}</li>
					                    @else
					                        <li class="breadcrumb-item text-nowrap"><a href="{{$url}}" class="text-light"><i class="icon-home"></i></a></li>
					                    @endif
					                @endforeach
					            </ol>
					            @endif
							</div>
						</div>
					</div>
					<div id="banner" class="slick-slider">
						<div class="hero-slide dark">
							@php
								$currentSlug = Str::afterLast(url()->current(), '/');
								$category = \App\Models\Ecommerce\ProductCategory::where('slug', $currentSlug)->first();
							@endphp

							@if($category != null)
								<img src="{{ env('APP_URL') . '/' . $category->banner_url }}" />
							@else
								<img src="{{ asset('theme/images/banners/sub1.jpg') }}" />
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section> --}}

{{-- <section id="slider" class="slick-wrapper clearfix">
    <div class="banner-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12" style="padding:0;">
                    <div class="sub-banner-caption">
                        <div class="container" style="position: relative;">
                            <h2 class="text-center excerpt-1 text-light">{{$page->name}}</h2>
                            <div class="sub-banner-flex">
                                <ol class="breadcrumb nobottommargin flex-nowrap justify-content-center">
                                    <li class="breadcrumb-item text-nowrap"><a href="index.htm" class="text-light"><i class="icon-home"></i></a></li>
                                    <li class="breadcrumb-item active excerpt-1 text-light" aria-current="page">{{$page->name}}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div id="banner" class="slick-slider">
                        <div class="hero-slide dark">
                            <img src="{{asset('storage/banners/no-banner.jpg')}}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<!-- IF NO BANNER -->
@if(str_contains(url()->current(), '/search-result'))
<section id="slider" class="slick-wrapper clearfix no-slider-banner" style="min-height: 10vh !important;">
	<!-- nothing -->
</section>
@else
<section id="slider" class="slick-wrapper clearfix no-slider-banner" style="min-height: 10vh !important;">
    <div class="banner-wrapper">
        <div class="container-fluid">
            <div class="row">
                {{-- <div class="col-lg-12" style="padding:0;">
                    <div class="sub-banner-caption">
                        <div class="container" style="position: relative;">
                            <h2 class="text-center excerpt-1 text-light">{{$page->name}}</h2>
                            <div class="sub-banner-flex">
                                <ol class="breadcrumb nobottommargin flex-nowrap justify-content-center">
                                    <li class="breadcrumb-item text-nowrap"><a href="index.htm" class="text-light"><i class="icon-home"></i></a></li>
                                    <li class="breadcrumb-item active excerpt-1 text-light" aria-current="page">{{$page->name}}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div id="banner" class="slick-slider">
                        <div class="hero-slide dark">
                            <img src="{{asset('storage/banners/no-banner.jpg')}}" />
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</section>
@endif