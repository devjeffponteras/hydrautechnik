@if(isset($page) && $page->album && count($page->album->banners) > 0 && $page->album->is_main_banner())
    @include('theme.layouts.banners.home-slider')
@elseif(isset($page) && $page->album && count($page->album->banners) > 1 && !$page->album->is_main_banner())
    @include('theme.layouts.banners.page-slider')
@elseif(isset($page) && (isset($page->album->banners) && (count($page->album->banners) == 1 && !$page->album->is_main_banner()) || !empty($page->image_url)))
    @include('theme.layouts.banners.page-banner')
@else
    @include('theme.layouts.banners.no-banner')
@endif
