@extends('theme.main')

@section('pagecss')
<style>
    /* Prevent horizontal page overflow and make images responsive */
    html, body { overflow-x: hidden; }
    *{ box-sizing: border-box; }
    img{ max-width:100%; height:auto; display:block; }
    .clients-logos-wrapper{ overflow:hidden; max-width:100vw; }
    .clients-logos{ will-change:transform; }
    /* Service row spacing and mobile centering */
    .service-row{ padding-bottom:30px; }
    /* Desktop gutters: make left/right padding symmetric inside each column so edges align */
    @media (min-width: 769px) {
        .service-row { padding-left: 10px; padding-right: 10px; }
        .service-row > .col-lg-6 { padding-left: 10px !important; padding-right: 10px !important; }
        .service-row .col-lg-6 img { display:block; margin:0 auto; }
    }
    @media (max-width: 768px){
        .service-row{ padding-bottom:18px; padding-left:20px; padding-right:20px; display:flex; flex-direction:column; }
        .service-row > .col-lg-6{ width:100% !important; max-width:100% !important; float:none !important; margin:0 auto; display:block; padding-left:8px !important; padding-right:8px !important; }

        /* Force image-first column on mobile regardless of desktop alternation */
        .service-row > .p-0{ order: -1 !important; }
        .service-row > .hidden-left, .service-row > .hidden-right{ order: 0 !important; }
        /* Keep internal text flow: label/title then description then button */
        .service-row p.fw-normal{ order: 1; text-align:justify; text-justify:inter-word; margin:0.75rem 0; max-width:100%; }
        .service-row .service-learn-more{ order: 2; display:inline-block; margin-top:12px; }

        /* Keep inner heading and button left-aligned on mobile */
        .service-row .heading-block, .service-row .service-learn-more{ text-align:left; }
        .service-row .heading-block h3{ text-align:left; }

        .service-row img{ margin:0 0 14px 0; width:100%; height:auto; box-shadow:none !important; }
        /* Hide the desktop-positioned image on mobile to avoid duplicates; show the mobile-only image above */
        .service-row > .p-0 img{ display:none !important; }
        .service-row .service-image-mobile img{ display:block !important; }
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

            <div class="row topmargin-lg clearfix service-row">

                {{-- Mobile-only image placed at top to guarantee image-first stacking on phones --}}
                <div class="col-12 d-block d-md-none service-image-mobile" style="padding-left:8px;padding-right:8px;">
                    <img src="{{ $img }}" style="width:100%; height:auto; box-shadow:none !important; margin-bottom:14px;">
                </div>

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

    // Mobile autoplay fallback (requestAnimationFrame) with touch/pointer pause
    (function(){
        try{
            if (typeof window === 'undefined') return;
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            var wrapper = document.querySelector('.clients-logos-wrapper');
            var logos = document.getElementById('clientsLogos') || (wrapper && wrapper.querySelector('.clients-logos'));
            if(!wrapper || !logos) return;

            // Only run autoplay on small screens (mobile). Desktop uses CSS animation.
            if(!window.matchMedia('(max-width: 768px)').matches) return;

            // Use transform-based autoplay to avoid expanding page width
            wrapper.style.overflowX = 'hidden';
            logos.style.willChange = 'transform';

            try{
                if(logos.children.length && logos.scrollWidth <= wrapper.clientWidth * 1.1){
                    var clone = logos.cloneNode(true);
                    logos.appendChild(clone);
                }
            } catch(e){ /* ignore */ }

            var speed = 60; // px per second
            var rafId = null; var lastTs = null; var paused = false; var pos = 0;

            function step(ts){
                if(!lastTs) lastTs = ts;
                var dt = (ts - lastTs) / 1000;
                lastTs = ts;
                if(!paused){
                    pos += speed * dt;
                    var trackWidth = logos.scrollWidth / 2 || logos.scrollWidth || 1;
                    if(pos >= trackWidth) pos = 0;
                    logos.style.transform = 'translateX(' + (-pos) + 'px)';
                }
                rafId = requestAnimationFrame(step);
            }

            function start(){ if(!rafId){ lastTs = null; rafId = requestAnimationFrame(step); } }
            function stop(){ if(rafId){ cancelAnimationFrame(rafId); rafId = null; lastTs = null; } }

            var pauseTimeout = null; var resumeDelay = 700;
            function setPausedYes(){ paused = true; if(pauseTimeout) clearTimeout(pauseTimeout); }
            function setPausedNo(){ if(pauseTimeout) clearTimeout(pauseTimeout); pauseTimeout = setTimeout(function(){ paused = false; }, resumeDelay); }

            wrapper.addEventListener('pointerdown', function(){ setPausedYes(); }, {passive:true});
            wrapper.addEventListener('pointerup', function(){ setPausedNo(); }, {passive:true});
            wrapper.addEventListener('touchstart', function(){ setPausedYes(); }, {passive:true});
            wrapper.addEventListener('touchend', function(){ setPausedNo(); }, {passive:true});
            wrapper.addEventListener('mouseenter', function(){ setPausedYes(); }, {passive:true});
            wrapper.addEventListener('mouseleave', function(){ setPausedNo(); }, {passive:true});

            var existingSlow = window.slowDownAnimation;
            var existingFast = window.speedUpAnimation;
            window.slowDownAnimation = function(){ try{ if(typeof existingSlow === 'function') existingSlow(); }catch(e){} speed = Math.max(10, speed - 20); };
            window.speedUpAnimation = function(){ try{ if(typeof existingFast === 'function') existingFast(); }catch(e){} speed = Math.min(600, speed + 20); };

            setTimeout(start, 200);
            setTimeout(start, 1200);

        }catch(e){ console && console.warn && console.warn('clients mobile autoplay error', e); }
    })();
</script>

@endsection
