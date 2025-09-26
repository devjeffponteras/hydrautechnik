@extends('theme.main')

@section('pagecss')
<style type="text/css">
    .side-menu.horizontal-tab ul.mb-0.pb-0 {
        display: flex;
    }
    .side-menu.horizontal-tab {
        border-bottom: 1px solid #d5d5d5;
    }
    .side-menu.horizontal-tab ul.mb-0.pb-0 li.current {
        border-color: #1abc9c !important;
        border-bottom: 1px solid;
    }
    .side-menu.horizontal-tab ul.mb-0.pb-0 li > a {
        border: none;
    }
    .side-menu.horizontal-tab ul.mb-0.pb-0 li > a div {
        padding: 15px 35px 15px 35px;
    }
    div#portfolio .entry.event .grid-inner.border .p-4 .entry-title.title-sm h3 {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection

@section('content')
<div class="container bottommargin-lg">
    <div class="row">
        <div class="col-lg-12">
            <div class="tablet-view">
                <a href="javascript:void(0)" class="closebtn d-block d-lg-none" onclick="closeNav()">&times;</a>

                <div class="card border-0">
                    {{-- <div class="border-0 mb-5">
                        <h3 class="mb-3">Search</h3>
                        <div class="search">
                            <form class="mb-0" id="frm_search">
                                <div class="searchbar">
                                    <input type="hidden" name="type" value="searchbox">
                                    <input type="text" name="criteria" id="searchtxt" class="form-control form-input form-search" placeholder="Search news" aria-label="Search news" aria-describedby="button-addon1" />
                                    <button class="form-submit-search" type="submit" id="button-addon2">
                                        <i class="icon-line-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="border-0 mb-5">
                        <h3 class="mb-3">News</h3>
                        <div class="side-menu">
                            {!! $dates !!}
                        </div>
                    </div> --}}

                    <div class="border-0 mb-5">
                        <!-- <h3 class="mb-3">Categories</h3> -->
                        <div class="side-menu horizontal-tab">
                            {!! $categories !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <span onclick="closeNav()" class="dark-curtain"></span>
        <div class="col-lg-12 col-md-5 col-sm-12">
            <span onclick="openNav()" class="button button-small button-circle border-bottom ms-0 text-initial nols fw-normal noleftmargin d-lg-none mb-4"><span class="icon-chevron-left me-2 color-2"></span> Filter</span>
        </div>
        <div class="col-lg-12">
            @if(isset($_GET['type']))
                @if($_GET['type'] == 'searchbox')
                    <div class="col-12">
                        @if($totalSearchedArticle > 0)
                            <div class="style-msg successmsg">
                                <div class="sb-msg"><i class="icon-thumbs-up"></i><strong>Woo hoo!</strong> We found <strong>(<span>{{ $totalSearchedArticle }}</span>)</strong> matching results.</div>
                            </div>
                        @else
                            <div class="style-msg2 errormsg">
                                <div class="msgtitle p-0 border-0">
                                    <div class="sb-msg">
                                        <i class="icon-thumbs-up"></i><strong>Uh oh</strong>! <span><strong>{{ app('request')->input('criteria') }}</strong></span> you say? Sorry, no results!
                                    </div>
                                </div>
                                <div class="sb-msg">
                                    <ul>
                                        <li>Check the spelling of your keywords.</li>
                                        <li>Try using fewer, different or more general keywords.</li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            @endif
            <div class="portfolio-container">

                <!-- Portfolio Items
                ============================================= -->
                <div id="portfolio" class="portfolio row gutter-20">

                    @forelse($articles as $article)
                        <div class="entry event col-lg-4 col-md-6">
                            <div
                                class="grid-inner row g-0 p-0 bg-transparent shadow-sm h-shadow all-ts h-translatey-sm card border">
                                <div class="w-100">
                                    <a href="{{ route('news.front.show',$article->slug) }}">
                                        @if($article->thumbnail_url)
                                            <img class="w-100" src="{{ $article->thumbnail_url }}" alt="{{ $article->name }}">
                                        @else
                                            <img class="w-100" src="{{ asset('storage/news_image/news_thumbnail/No_Image_Available.jpg')}}" alt="{{ $article->name }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="p-4">
                                    <div class="entry-title title-sm">
                                        <h3 class="font-size: 14px"><a href="{{ route('news.front.show',$article->slug) }}">{{ $article->name }}</a>
                                        </h3>
                                    </div>
                                    <div class="entry-meta" style="font-size: 12px;">
                                        <ul class="small">
                                            <li><i class="icon-calendar3"></i> {{ Setting::news_date_format($article->date) }}</li>
                                            <li><i class="icon-user"></i> Admin</li>
                                            {{-- <li><i class="icon-folder-open"></i> <a href="#">{{$article->category->name}}</a></li> --}}
                                        </ul>
                                    </div>
                                    <div class="entry-content mt-3" style="font-size: 14px;">
                                        <p>{{ $article->teaser }}</p>
                                        <a href="{{ route('news.front.show',$article->slug) }}"
                                            class="btn btn-outline-secondary button-3d button-icon-effect button-icon-flip-y"
                                            style="font-size: 12px;">
                                            <i class="uil uil-angle-right-b"></i> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    {{-- <article class="portfolio-item">
                        <div class="grid-inner row g-0">
                            <div class="col-md-5">
                                <div class="news-imag">
                                    @if($article->thumbnail_url)
                                        <img class="w-100 h-100 position-relative position-lg-absolute inset-0 object-position-center object-fit-cover" src="{{ $article->thumbnail_url }}" alt="{{ $article->name }}">
                                    @else
                                        <img class="w-100 h-100 position-relative position-lg-absolute inset-0 object-position-center object-fit-cover" src="{{ asset('storage/news_image/news_thumbnail/No_Image_Available.jpg')}}" alt="{{ $article->name }}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7 ps-md-4">
                                <div class="entry-title title-sm">
                                    <h2><a href="{{ route('news.front.show',$article->slug) }}">{{ $article->name }}</a></h2>
                                </div>
                                <div class="entry-meta">
                                    <ul class="small">
                                        <li><i class="icon-calendar3"></i> {{ Setting::news_date_format($article->date) }}</li>
                                        <li><i class="icon-user"></i> Admin</li>
                                        <li><i class="icon-folder-open"></i> <a href="#">{{$article->category->name}}</a></li>
                                    </ul>
                                </div>
                                <div class="entry-content">
                                    <p>{{ $article->teaser }}</p>
                                    <a href="{{ route('news.front.show',$article->slug) }}" class="button button-small button-circle border-bottom ms-0 text-initial nols fw-normal">Read More <i class="icon-line-arrow-right color-2 ms-2 me-0"></i></a>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-4">
                    </article> --}}
                @empty

                @endforelse
                </div>

                {{ $articles->links('theme.layouts.pagination') }}

            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script>
        $('#frm_search').on('submit', function(e) {
            e.preventDefault();
            window.location.href = "{{route('news.front.index')}}?type=searchbox&criteria="+$('#searchtxt').val();
        });
    </script>
@endsection
