<!-- Top Bar
============================================= -->
<div id="top-bar" class="py-3 px-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-md-row fw-medium text-center text-white">
            <!-- vertisal content -->
            <div class="social-wrap">
                <span class="mx-2"><a href="#" class="text-white"><i class="bi-facebook" style="font-size: 18px;"></i></a></span>
                <span class="mx-2"><a href="#" class="text-white"><i class="bi-instagram" style="font-size: 18px;"></i></a></span>
            </div>
            <div class="header-title d-flex justify-content-end align-items-center">
                <p class="text-white mb-0">Sales: (02) 8 441 8116 Marketing: (02) 771 921 34 &nbsp;&nbsp;&nbsp;&nbsp;|</p>
                <!-- Top Search
                ============================================= -->
                <div id="top-search" class="header-misc-icon ps-2">
                    <a href="#" id="top-search-trigger">
                        <i class="uil uil-search text-white position-relative"></i>
                        <i class="bi-x-lg position-relative"></i>
                    </a>
                </div>
            </div>
        </div>
        <form class="top-search-form" action="{{ route('search.result') }}" method="get">
            <input type="text" name="searchtxt" class="form-control" value="" placeholder="Search..." autocomplete="off" style="padding-left: 175px;">
        </form>

    </div>
</div>

<!-- Header
============================================= -->
<header id="header" class="header-size-sm transparent-header floating-header" data-sticky-shrink="false">
	<div id="header-wrap border-0">

		<div class="container-fluid" data-class="up-lg:border up-lg:shadow-sm" style="padding-right: 0px;">
			<div class="header-row d-flex justify-content-between">

                <div class="d-flex justify-content-start">
                    <!-- Logo
    				============================================= -->
                    <div id="header-logo" class="px-3 py-3">
                        <a href="index.html">
                            <img src="{{ asset('/theme/addons/images/logos/logo-main.png') }}" alt="logo" style="min-height: 70px; padding-right: 18px; border-right: 1px solid #d9d9d9 !important;">
                        </a>
                    </div><!-- #logo end -->
    				<!-- Primary Navigation
    				============================================= -->
    				<nav class="primary-menu with-arrows">

    					@include('theme.layouts.components.menu')

    				</nav><!-- #primary-menu end -->
                </div>

                <!-- button when mobile -->
                <div class="primary-menu-trigger">
                    <button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
                        <span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
                    </button>
                </div>

                <!-- call us btn -->
                <div class="d-flex call-us-wide-btn d-flex align-items-center">
                    <i class="icon-line-arrow-right" style="font-size: 24px"></i>
                    <a href="#" class="text-dark ps-1" style="font-size: 24px"> Call us: ﻿+632 8546 8665</a>
                </div>

			</div>
		</div>

	</div>
	<!-- <div class="header-wrap-clone"></div> -->
</header><!-- #header end -->

@include('theme.layouts.components.alert')