@extends('theme.main')

@section('pagecss')
    <style>
        {{ str_replace(array("'", "&#039;"), "", $page->styles ) }}
    </style>
@endsection
 
@section('content')

@php
    $page = \App\Models\Page::where('slug', $page->slug)->first();
@endphp

<div class="container bottommargin-lg">
    <div class="row">
        @if($parentPage)
            {{-- <div class="col-lg-3">
                <span onclick="openNav()" class="d-lg-none mb-4 btn btn-primary btn-bg"><i class="icon-list-alt"></i></span>
                
                <div id="mySidenav">
                    <a href="javascript:void(0)" class="closebtn d-lg-none" onclick="closeNav()">&times;</a>

                    <div class="card border-0">
                        <h3>Quicklinks</h3>
                        <div class="side-menu">
                            <ul class="mb-0 pb-0">
                                <li @if($parentPage->id == $page->id) class="active" @endif>
                                    <a @if($parentPage->id == $page->id) class="active" @endif href="{{ $parentPage->get_url() }}"><div>{{ $parentPage->name }}</div></a>
                                </li>
                                @foreach($parentPage->sub_pages as $subPage)
                                    <li @if($subPage->id == $page->id || Str::contains(url()->current(), $subPage->get_url())) class="active" @endif>
                                        <a @if($subPage->id == $page->id) class="active" @endif href="{{ $subPage->get_url() }}"><div>{{ $subPage->name }}</div></a>
                                        @if ($subPage->has_sub_pages())
                                            <ul>
                                                @foreach ($subPage->sub_pages as $subSubPage)
                                                <li @if ($subSubPage->id == $page->id || Str::contains(url()->current(), $subSubPage->get_url())) class="active" @endif>
                                                    <a @if($subSubPage->id == $page->id || Str::contains(url()->current(), $subSubPage->get_url())) class="active" @endif href="{{ $subSubPage->get_url() }}"><div>{{ $subSubPage->name }}</div></a>
                                                    @if ($subSubPage->has_sub_pages())
                                                    <ul>
                                                        @foreach ($subSubPage->sub_pages as $subSubSubPage)
                                                            <li @if ($subSubSubPage->id == $page->id || Str::contains(url()->current(), $subSubSubPage->get_url())) class="active" @endif>
                                                                <a @if($subSubSubPage->id == $page->id || Str::contains(url()->current(), $subSubSubPage->get_url())) class="active" @endif href="{{ $subSubSubPage->get_url() }}"><div>{{ $subSubSubPage->name }}</div></a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <div class="heading-block">
                        <h3>{{ $page->name }}</h3>
                    </div>
                    
                    <ul class="quicklinks mb-3">
                        <li @if($parentPage->id == $page->id) class="active" @endif>{{-- style="background-color: #287f31;" 
                            <a @if($parentPage->id == $page->id) class="active" @endif href="{{ $parentPage->get_url() }}"><div>{{ $parentPage->name }}</div></a>
                        </li>
                        @foreach($parentPage->sub_pages as $subPage)
                            <li @if($subPage->id == $page->id || Str::contains(url()->current(), $subPage->get_url())) class="active" @endif>
                                <a @if($subPage->id == $page->id) class="active" @endif href="{{ $subPage->get_url() }}"><div>{{ $subPage->name }}</div></a>{{-- style="color: #ffc107!important;" 
                                @if ($subPage->has_sub_pages())
                                    <ul>
                                        @foreach ($subPage->sub_pages as $subSubPage)
                                        <li @if ($subSubPage->id == $page->id || Str::contains(url()->current(), $subSubPage->get_url())) class="active" @endif>
                                            <a @if($subSubPage->id == $page->id || Str::contains(url()->current(), $subSubPage->get_url())) class="active" @endif href="{{ $subSubPage->get_url() }}"><div>{{ $subSubPage->name }}</div></a>
                                            @if ($subSubPage->has_sub_pages())
                                            <ul>
                                                @foreach ($subSubPage->sub_pages as $subSubSubPage)
                                                    <li @if ($subSubSubPage->id == $page->id || Str::contains(url()->current(), $subSubSubPage->get_url())) class="active" @endif>
                                                        <a @if($subSubSubPage->id == $page->id || Str::contains(url()->current(), $subSubSubPage->get_url())) class="active" @endif href="{{ $subSubSubPage->get_url() }}"><div>{{ $subSubSubPage->name }}</div></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div> --}}
            
            <div class="col-lg-12">
                {!! $page->contents !!}
            </div>
        @else
            <div class="col-lg-12">
                {!! $page->contents !!}
            </div>
        @endif
    </div>
</div>

@endsection
