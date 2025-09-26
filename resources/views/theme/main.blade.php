<!DOCTYPE html>
<html dir="ltr" lang="en-US">


@include('theme.layouts.components.styles')


 <body class="stretched is-expanded-menu">
	
	<!-- Cart Panel Background
	============================================= -->
	<div class="body-overlay"></div>

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">
		
		<!-- Header
		============================================= -->
		@include('theme.layouts.components.header')<!-- #header end -->

		<!-- Slider
		============================================= -->
		@include('theme.layouts.components.banner')
		
		<!-- #slider end -->

		<!-- Content
		============================================= -->
		<section id="website-content">

			@yield('content')
			
		</section><!-- #content end -->

		<!-- Footer
		============================================= -->
		<footer id="footer" class="bg-transparent border-0">
			@include('theme.layouts.footer')
		</footer><!-- #footer end -->

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>

	{{-- /* FOR CAPTCHA */ --}}

	@include('theme.layouts.components.scripts')

	{{-- /* FOR ANALYTICS */ --}}

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-HR35693H16"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-HR35693H16');
	</script>

</body>
</html>