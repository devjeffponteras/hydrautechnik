@php
    // If we're on the About Us page, do not render any banner or banner partials.
    // This prevents any banner HTML/CSS/JS from loading for that page.
    $isAbout = (isset($page) && isset($page->slug) && $page->slug === 'about-us') || Request::is('about-us');
@endphp

@if($isAbout)
    {{-- About Us: intentionally no banner output --}}
@else
    @if(isset($page) && $page->album && count($page->album->banners) > 0 && $page->album->is_main_banner())
        @include('theme.layouts.banners.home-slider')
    @elseif(isset($page) && $page->album && count($page->album->banners) > 1 && !$page->album->is_main_banner())
        @include('theme.layouts.banners.page-slider')
    @elseif(isset($page) && (isset($page->album->banners) && (count($page->album->banners) == 1 && !$page->album->is_main_banner()) || !empty($page->image_url)))
        @include('theme.layouts.banners.page-banner')
    @else
        @include('theme.layouts.banners.no-banner')
    @endif
@endif
