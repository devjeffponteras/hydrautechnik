@extends('theme.main')


@section('content')
<div class="container topmargin-lg bottommargin-lg">
    <style>
        /* Social icons: larger size and subtle hover highlight */
        .news-share .social-icon {
            margin-right: 8px !important;
            padding: 6px 8px !important;
            border-radius: 6px !important;
            background: transparent !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            transition: background .15s ease, color .15s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none !important;
        }

        .news-share .social-icon i {
            font-size: 20px !important;
            color: #444 !important;
            line-height: 1 !important;
            transition: color .15s ease !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }

        .news-share .social-icon:hover {
            background: rgba(30,136,229,0.08) !important;
        }

        .news-share .social-icon:hover i {
            color: #1e88e5 !important;
        }
    </style>
    <div class="row">
        <span onclick="closeNav()" class="dark-curtain"></span>
        <div class="col-lg-12 col-md-5 col-sm-12">
            <span onclick="openNav()" class="button button-small button-circle border-bottom ms-0 text-initial nols fw-normal noleftmargin d-lg-none mb-4"><span class="icon-chevron-left me-2 color-2"></span> Filter</span>
        </div>
        <div class="col-lg-3 pe-lg-4">
            <div class="tablet-view">
                <a href="javascript:void(0)" class="closebtn d-block d-lg-none" onclick="closeNav()">&times;</a>

                <div class="card border-0">
                    <div class="border-0 mb-5">
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
                    </div>

                    <div class="border-0 mb-5">
                        <h3 class="mb-3">Categories</h3>
                        <div class="side-menu">
                            {!! $categories !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="article-card">
                <div class="article-title">
                    <h2>{{$news->name}}</h2>
                </div>
                <div class="article-meta">
                    <div class="entry-meta mb-3">
                        <ul class="small">
                            <li><i class="icon-calendar3"></i> {{ Setting::news_date_format($news->date) }}</li>
                            <li><i class="icon-user"></i> admin</li>
                            <li><i class="icon-folder-open"></i> <a href="#">{{$news->category->name}}</a></li>
                        </ul>
                    </div>
                    <hr class="mb-4" />
                </div>

                {{-- @if (!empty($news->image_url))
                    <div class="article-image">
                        <img class="mb-5 w-100" src="{{$news->image_url}}" alt="{{$news->name}}">
                    </div>
                @endif --}}

                <div class="news-desc mb-6">
                    {!! $news->contents !!}
                </div>
                <div class="news-share mb-5">
                    <h5>Share:</h5>
                    <?php $shareUrl = urlencode(route('news.front.show', $news->slug)); ?>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                       class="social-icon si-rounded si-facebook"
                       rel="noopener noreferrer"
                       onclick="window.open(this.href,'_blank','noopener'); return false;">
                        <i class="icon-facebook"></i>
                    </a>

                    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&amp;text={{ urlencode('Check this out') }}"
                       class="social-icon si-rounded si-twitter"
                       rel="noopener noreferrer"
                       onclick="window.open(this.href,'_blank','noopener'); return false;">
                        <i class="icon-twitter"></i>
                    </a>

                    <a href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ $shareUrl }}"
                       class="social-icon si-rounded si-linkedin"
                       rel="noopener noreferrer"
                       onclick="window.open(this.href,'_blank','noopener'); return false;">
                        <i class="icon-linkedin"></i>
                    </a>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('#frm_search').on('submit', function(e) {
            e.preventDefault();
            window.location.href = "{{route('news.front.index')}}?type=searchbox&criteria="+$('#searchtxt').val();
        });
    });
</script>
@endsection
