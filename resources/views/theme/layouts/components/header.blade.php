@include('theme.layouts.top-bar')



<!-- Header
============================================= -->
<header id="header" class="header-size-sm transparent-header floating-header shadow" data-sticky-shrink="false" style="background:#ffffff;">
    <div id="header-wrap border-0" style="background:transparent;">

		<div class="container-fluid" data-class="up-lg:border up-lg:shadow-sm" style="padding-right: 0px;">
			<div class="header-row d-flex justify-content-between">

                <div class="d-flex justify-content-start">
                    <!-- Logo
    				============================================= -->
                    <div id="header-logo" class="px-3 py-3">
                        <a href="{{env('APP_URL')}}/home">
                            <img src="{{ asset('/theme/addons/images/logos/logo-main.png') }}" alt="logo">
                        </a>
                    </div><!-- #logo end -->

    				<!-- Primary Navigation
    				============================================= -->
    				<nav class="primary-menu with-arrows">

    					@include('theme.layouts.components.menu')

    				</nav><!-- #primary-menu end -->

                    <div id="primary-menu-trigger">
                        <svg class="svg-trigger" viewBox="0 0 100 100"><path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path><path d="m 30,50 h 40"></path><path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path></svg>
                    </div>
                </div>

                <!-- button when mobile -->
                <!-- <div class="primary-menu-trigger">
                    <button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
                        <span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
                    </button>
                </div> -->

                {{-- <!-- call us btn -->
                <div class="d-flex call-us-wide-btn d-flex align-items-center">
                    <i class="icon-line-arrow-right" style="font-size: 20px"></i>
                    <a href="#" class="text-dark ps-1 call-us-header-number" style="font-size: 24px">
                        Call us: {{ optional(App\Helpers\Setting::info())->mobile_no ?? '' }}
                    </a>
                </div> --}}

			</div>
		</div>

	</div>
	<!-- <div class="header-wrap-clone"></div> -->
</header><!-- #header end -->

@include('theme.layouts.components.alert')
