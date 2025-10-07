@extends('theme.main')

@section('pagecss')

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

@endphp

@section('content')

    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <p class="text-center topmargin-lg faded-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl. Fusce eleifend ullamcorper lacinia..</p>
        </div>

        <!-- row per services -->
        <div class="row topmargin-lg clearfix" style="padding-bottom: 30px;">
            <!-- Image Texts
            ============================================= -->
            <div class="col-lg-6 hidden-left" style="padding-right: 80px;">
                <div class="heading-block topmargin-sm bottommargin-sm border-0">
                    <p class="mb-0 faded-text">Services</p>
                    <h3 class="nott" style="font-size: 36px; font-weight: 500; text-align: left;">Lubrication and Hydraulic Pipes System Flushing</h3>
                </div>
                <p class="fw-normal faded-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl. Fusce eleifend ullamcorper lacinia.</p>
                
                <button class="btn btn-lg btn-warning" style="border-radius: 0px; font-weight: 500; padding: 14px 18px;">
                    Learn More <i class="icon-line-arrow-right"></i>
                </button>
            </div>

            <!-- Image
            ============================================= -->
            <div class="col-lg-6 p-0 hidden-right">
                <img src="{{ asset('/images/services/hyd1.jpg') }}" style="box-shadow: -20px 20px 0px -5px rgb(0 0 0 / 14%); max-width: 560px; max-height: 350px;">
            </div>
        </div>

        <div class="row topmargin-lg clearfix" style="padding-bottom: 30px;">

            <!-- Image
            ============================================= -->
            <div class="col-lg-6 p-0 hidden-left">
                <img src="{{ asset('/images/services/hyd2.jpg') }}" style="box-shadow: -20px 20px 0px -5px rgb(0 0 0 / 14%); max-width: 560px; max-height: 350px;">
            </div>

            <!-- Image Texts
            ============================================= -->
            <div class="col-lg-6 hidden-right" style="padding-right: 80px;">
                <div class="heading-block topmargin-sm bottommargin-sm border-0">
                    <p class="mb-0 faded-text">Services</p>
                    <h3 class="nott" style="font-size: 36px; font-weight: 500; text-align: left;">Troubleshooting of Hydraulics</h3>
                </div>
                <p class="fw-normal faded-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl. Fusce eleifend ullamcorper lacinia.</p>
                
                <button class="btn btn-lg btn-warning" style="border-radius: 0px; font-weight: 500; padding: 14px 18px;">
                    Learn More <i class="icon-line-arrow-right"></i>
                </button>
            </div>

        </div>

        <div class="row topmargin-lg clearfix" style="padding-bottom: 30px;">
            <!-- Image Texts
            ============================================= -->
            <div class="col-lg-6 hidden-left" style="padding-right: 80px;">
                <div class="heading-block topmargin-sm bottommargin-sm border-0">
                    <p class="mb-0 faded-text">Services</p>
                    <h3 class="nott" style="font-size: 36px; font-weight: 500; text-align: left;">Repair of Hydraulics and Design  Fabrication</h3>
                </div>
                <p class="fw-normal faded-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl. Fusce eleifend ullamcorper lacinia.</p>
                
                <button class="btn btn-lg btn-warning" style="border-radius: 0px; font-weight: 500; padding: 14px 18px;">
                    Learn More <i class="icon-line-arrow-right"></i>
                </button>
            </div>

            <!-- Image
            ============================================= -->
            <div class="col-lg-6 p-0 hidden-right">
                <img src="{{ asset('/images/services/hyd3.jpg') }}" style="box-shadow: -20px 20px 0px -5px rgb(0 0 0 / 14%); max-width: 560px; max-height: 350px;">
            </div>
        </div>

        <div class="row topmargin-lg clearfix" style="padding-bottom: 30px;">

            <!-- Image
            ============================================= -->
            <div class="col-lg-6 p-0 hidden-left">
                <img src="{{ asset('/images/services/hyd4.jpg') }}" style="box-shadow: -20px 20px 0px -5px rgb(0 0 0 / 14%); max-width: 560px; max-height: 350px;">
            </div>

            <!-- Image Texts
            ============================================= -->
            <div class="col-lg-6 hidden-right" style="padding-right: 80px;">
                <div class="heading-block topmargin-sm bottommargin-sm border-0">
                    <p class="mb-0 faded-text">Services</p>
                    <h3 class="nott" style="font-size: 36px; font-weight: 500; text-align: left;">Failure & Damage Analysis Repair of Hydraulics</h3>
                </div>
                <p class="fw-normal faded-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl. Fusce eleifend ullamcorper lacinia.</p>
                
                <button class="btn btn-lg btn-warning" style="border-radius: 0px; font-weight: 500; padding: 14px 18px;">
                    Learn More <i class="icon-line-arrow-right"></i>
                </button>
            </div>

        </div>

    </div>

    <!-- Parallax Area
    ============================================= -->
    <div class="section home-bot-prallax parallax dark mb-0" style="background-image: url({{ asset('/theme/images/banners/footer-hero.jpeg')}}); padding: 100px 0;" data-bottom-top="background-position:0px 0px;" data-top-bottom="background-position:0px -300px;">

        <div class="heading-block center mb-2">
            <h3 style="font-size: 50px; font-weight: 400;">Got Questions?</h3>
        </div>

        <div class="fslider testimonial testimonial-full" data-animation="fade" data-arrows="false">
            <div class="flexslider">
                <div class="slider-wrap">
                    <div class="slide">
                        <p class="text-center" style="font-weight: 300; font-size: 24px;">Contact Us about our services by sending us an inquiry.</p>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>


    {!! $contents !!}

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
</script>
@endsection