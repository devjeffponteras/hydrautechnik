@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
<div class="section sub-pages-hyd-container mt-0 pt-0" style="background-color: white;">
	<div class="container-fluid">

		<div class="row col-12 px-3">

			<!-- left side nav -->
			<x-side-navigation></x-side-navigation>

			<!-- main content -->
			<div class="col-12 col-md-10">
				<p class="pb-2" style="opacity: .8;">
					<small>
						<a href="{{ route('products') }}">Hydac Filtration Systems</a>
						 > 
						 <a href="{{ route('sub-products') }}">Hydac Dewatering and Other Fluid Conditioning</a>
						 > 
						 <span style="text-decoration: underline;">Hydac Fluid Conditioning Systems</span></small>
					<h3>
						Hydac Dewatering and Other Fluid Conditioning
					</h3>
				</p>

				<div class="row col-12">
					<div class="col-12 col-md-6 p-3 pt-0">
						<div class="view-sub-heading">
							<small>
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl. Fusce eleifend ullamcorper lacinia.
							</small>
						</div>
						<div class="view-bullets px-3 pt-3">
							<ul>
								<li>Massa venenatis sapien</li>
								<li>Enenatis massa</li>
								<li>Fusce eleifend</li>
								<li>Finibus congue</li>
							</ul>
						</div>

						<div class="view-accordion">
							<div class="toggle toggle-border">
								<div class="toggle-header">
									<div class="toggle-icon">
										<i class="toggle-closed uil uil-plus"></i>
										<i class="toggle-open uil uil-minus"></i>
									</div>
									<div class="toggle-title">
										IXU
									</div>
								</div>
								<div class="toggle-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, dolorum, vero ipsum molestiae minima odio quo voluptate illum excepturi quam cum voluptates doloribus quae nisi tempore necessitatibus dolores ducimus enim libero eaque explicabo suscipit animi at quaerat aliquid ex expedita perspiciatis? Saepe, aperiam, nam unde quas beatae vero vitae nulla.</div>
							</div>
							<div class="toggle toggle-border">
								<div class="toggle-header">
									<div class="toggle-icon">
										<i class="toggle-closed uil uil-plus"></i>
										<i class="toggle-open uil uil-minus"></i>
									</div>
									<div class="toggle-title">
										OLX
									</div>
								</div>
								<div class="toggle-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, dolorum, vero ipsum molestiae minima odio quo voluptate illum excepturi quam cum voluptates doloribus quae nisi tempore necessitatibus dolores ducimus enim libero eaque explicabo suscipit animi at quaerat aliquid ex expedita perspiciatis? Saepe, aperiam, nam unde quas beatae vero vitae nulla.</div>
							</div>
							<div class="toggle toggle-border">
								<div class="toggle-header">
									<div class="toggle-icon">
										<i class="toggle-closed uil uil-plus"></i>
										<i class="toggle-open uil uil-minus"></i>
									</div>
									<div class="toggle-title">
										FAM ATEX
									</div>
								</div>
								<div class="toggle-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, dolorum, vero ipsum molestiae minima odio quo voluptate illum excepturi quam cum voluptates doloribus quae nisi tempore necessitatibus dolores ducimus enim libero eaque explicabo suscipit animi at quaerat aliquid ex expedita perspiciatis? Saepe, aperiam, nam unde quas beatae vero vitae nulla.</div>
							</div>
							<div class="toggle toggle-border">
								<div class="toggle-header">
									<div class="toggle-icon">
										<i class="toggle-closed uil uil-plus"></i>
										<i class="toggle-open uil uil-minus"></i>
									</div>
									<div class="toggle-title">
										OLSW
									</div>
								</div>
								<div class="toggle-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, dolorum, vero ipsum molestiae minima odio quo voluptate illum excepturi quam cum voluptates doloribus quae nisi tempore necessitatibus dolores ducimus enim libero eaque explicabo suscipit animi at quaerat aliquid ex expedita perspiciatis? Saepe, aperiam, nam unde quas beatae vero vitae nulla.</div>
							</div>
						</div>

					</div>
					<div class="col-12 col-md-6">
						<div class="card side-panel-nav rounded bg-white shadow p-4">
							<img src="images/products/prd4.jpg">
						</div>
					</div>
				</div>

			</div>
		</div>
		
	</div>
</div>
@endsection

@section('pagejs')
<script>

</script>
@endsection