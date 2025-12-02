@extends('theme.main')

@section('pagecss')
<style>
    .title-tile {
        background-color: #252525;
        color: white;
        font-size: 28px;
        font-weight: 600;
        min-height: 150px;
        padding: 10px;
    }
    .owl-carousel .owl-stage-outer .owl-stage {
        display: flex;
        align-items: center;
        gap: 30px;
    }
</style>
@endsection

@php
    $contents = $page->contents;

// LATEST NEWS
    $featuredArticles = Article::where('is_featured', 1)->where('status', 'Published')->skip(0)->take(3)->get();
    if($featuredArticles->count()) {

        $featuredArticlesHTML = '';

        $prefooter = asset('theme/images/pre-footer.jpg');

        foreach ($featuredArticles as $index => $article) {
            $imageUrl = (empty($article->thumbnail_url)) ? asset('theme/images/misc/no-image.jpg') : $article->thumbnail_url;


            $featuredArticlesHTML .= '

                <div class="slide" data-thumb="'. $imageUrl .'">
                    <a href="'. $article->get_url() .'" class="d-block position-relative">
                        <div class="row">
                            <div class="col-md-6 half-one position-default">
                                <div class="floating-panel">
                                    <h2 class="h2 fw-semibold lh-base" style="margin-bottom: 0px;">'. $article->name .'</h2>
                                    <small style="color: #878787;">Date posted: '. $article->date_posted() .'</small>
                                    <p class="text-muted mt-4">'. $article->teaser .'</p>
                                    <a href="'. $article->get_url() .'" class="button button-3d button-mini button-rounded button-blue">Learn More &nbsp; ></a>
                                </div>
                            </div>
                            <div class="col-md-6 p-5">
                                <img class="rounded-corners" src="'. $imageUrl .'" alt="modair">
                            </div>
                        </div>
                    </a>
                </div>

                ';

            if (Article::has_featured_limit() && $index >= env('FEATURED_NEWS_LIMIT')) {
                break;
            }
        }

    } else {
        $featuredArticlesHTML = '';
    }

    $keywords   = ['{Featured Articles}'];
    $variables  = [$featuredArticlesHTML];
    $contents = str_replace($keywords,$variables,$contents);

    // Allow splitting the CMS contents into top/bottom using <!-- split --> marker
    $rawContents = $contents;
    $splitMarker = '<!-- split -->';
    $splitMarkerAlt = '<!-- SPLIT -->';
    $topContents = $rawContents;
    $bottomContents = '';
    if (strpos($rawContents, $splitMarker) !== false) {
        [$topContents, $bottomContents] = explode($splitMarker, $rawContents, 2);
    } elseif (strpos($rawContents, $splitMarkerAlt) !== false) {
        [$topContents, $bottomContents] = explode($splitMarkerAlt, $rawContents, 2);
    }

@endphp

@section('content')

    <div class="container">

             {!! $topContents !!}


        <!-- row per services (dynamic from DB; preserve design and alternation) -->
        @php
            try {
                // Load all published services for home page
                $homeServices = \App\Models\Service::where('status', 'PUBLISHED')->orderBy('name', 'asc')->get();
            } catch (\Throwable $e) {
                $homeServices = collect();
            }

            // Default images to fall back to (match original order)
            $defaultImgs = [
                asset('/images/services/hyd1.jpg'),
                asset('/images/services/hyd2.jpg'),
                asset('/images/services/hyd3.jpg'),
                asset('/images/services/hyd4.jpg'),
            ];
        @endphp

        @forelse($homeServices as $index => $service)
            @php
                $i = $index; // zero-based
                $img = '';
                if (!empty($service->image)) {
                    $img = asset($service->image);
                } else {
                    $img = $defaultImgs[$i % count($defaultImgs)];
                }
                $title = $service->name ?? 'Service';
                $desc = \Illuminate\Support\Str::limit(strip_tags($service->description ?? ''), 180);
                $reverse = ($i % 2 == 1);
            @endphp

            <div class="row topmargin-lg clearfix" style="padding-bottom: 30px;">

                @if(!$reverse)
                    <!-- Image Texts (left text, right image) -->
                    <div class="col-lg-6 hidden-left" style="padding-right: 80px;">
                        <div class="heading-block topmargin-sm bottommargin-sm border-0">
                            <p class="mb-0 faded-text">Services</p>
                            <h3 class="nott" style="font-size: 36px; font-weight: 500; text-align: left;">{{ $title }}</h3>
                        </div>
                        <p class="fw-normal faded-text">{{ $desc }}</p>

                        <a href="{{ route('company-capabilities.show', $service->id) }}" class="btn btn-lg btn-warning service-learn-more" data-id="{{ $service->id }}" data-url="{{ route('company-capabilities.show', $service->id) }}" style="border-radius: 0px; font-weight: 500; padding: 14px 18px;">Learn More <i class="icon-line-arrow-right"></i></a>
                    </div>

                    <!-- Image (right) -->
                    <div class="col-lg-6 p-0 hidden-right">
                        <img src="{{ $img }}" style="box-shadow: -20px 20px 0px -5px rgb(0 0 0 / 14%); max-width: 560px; max-height: 350px;">
                    </div>
                @else
                    <!-- Image (left) -->
                    <div class="col-lg-6 p-0 hidden-left">
                        <img src="{{ $img }}" style="box-shadow: -20px 20px 0px -5px rgb(0 0 0 / 14%); max-width: 560px; max-height: 350px;">
                    </div>

                    <!-- Image Texts (right text) -->
                    <div class="col-lg-6 hidden-right" style="padding-right: 80px;">
                        <div class="heading-block topmargin-sm bottommargin-sm border-0">
                            <p class="mb-0 faded-text">Services</p>
                            <h3 class="nott" style="font-size: 36px; font-weight: 500; text-align: left;">{{ $title }}</h3>
                        </div>
                        <p class="fw-normal faded-text">{{ $desc }}</p>

                        <a href="{{ route('company-capabilities.show', $service->id) }}" class="btn btn-lg btn-warning service-learn-more" data-id="{{ $service->id }}" data-url="{{ route('company-capabilities.show', $service->id) }}" style="border-radius: 0px; font-weight: 500; padding: 14px 18px;">Learn More <i class="icon-line-arrow-right"></i></a>
                    </div>
                @endif

            </div>
        @empty
            {{-- Fallback: show nothing --}}
        @endforelse

    </div>

    <div class="container-fluid p-0">
        {{-- CMS-managed content for clients/carousel/parallax (full width).
             Render bottom content only when editor added a <!-- split --> marker
             and when bottom content is not identical to the top content. --}}
        @php
            $topTrim = trim($topContents ?? '');
            $bottomTrim = trim($bottomContents ?? '');
        @endphp
        @if(!empty($bottomTrim) && $bottomTrim !== $topTrim)
            {!! $bottomContents !!}
        @endif
    </div>




@endsection


@section('pagejs')
<script>

    const observerLeft = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show-left');
        }
      });
    });

    const observerRight = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show-right');
        }
      });
    });

    document.querySelectorAll('.hidden-left').forEach((el) => observerLeft.observe(el));
    document.querySelectorAll('.hidden-right').forEach((el) => observerRight.observe(el));

    // Client logos animation speed controls
    let animationSpeed = 20;
    const logosElement = document.getElementById('clientsLogos');

    function slowDownAnimation() {
        if (!logosElement) return;
        animationSpeed = Math.min(40, animationSpeed + 5);
        logosElement.style.animationDuration = animationSpeed + 's';
    }

    function speedUpAnimation() {
        if (!logosElement) return;
        animationSpeed = Math.max(10, animationSpeed - 5);
        logosElement.style.animationDuration = animationSpeed + 's';
    }
</script>

@endsection
