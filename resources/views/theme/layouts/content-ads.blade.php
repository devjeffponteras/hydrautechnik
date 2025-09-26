@php
    $contents = Setting::getAds()->contents;
    $styles = Setting::getAds()->styles;
@endphp


<style>
    {!! $styles !!}
</style>

{!! $contents !!}