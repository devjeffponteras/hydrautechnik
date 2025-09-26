@php
    $photoUrl = (isset($page->album->banners) && count($page->album->banners) == 1) ? $page->album->banners[0]->image_path : $page->image_url;
@endphp

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
                            <img src="{{ $photoUrl }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<section id="slider" class="slick-wrapper clearfix include-header">{{-- .include-header --}}
    <div class="banner-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12" style="padding:0;">
                    <div class="sub-banner-caption">
                        <div class="container" style="position: relative;">
                            <h2 class="text-center excerpt-1 text-light">{{$page->name}}</h2>
                            <div class="sub-banner-flex">
                                <ol class="breadcrumb nobottommargin flex-nowrap justify-content-center">
                                    <li class="breadcrumb-item text-nowrap"><a href="{{ route('home') }}" class="text-light"><i class="icon-home"></i></a></li>
                                    <li class="breadcrumb-item active excerpt-1 text-light" aria-current="page">{{$page->name}}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div id="banner" class="slick-slider"> 
                        <div class="hero-slide dark">
                            <img src="{{$photoUrl}}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
