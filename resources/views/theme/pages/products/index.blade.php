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
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque pretium, dui vel efficitur elementum, dui massa venenatis sapien, non luctus neque nibh at enim. Pellentesque ornare, augue maximus finibus congue, nisl nunc gravida sem, a venenatis massa quam id nisl. Fusce eleifend ullamcorper lacinia.
					</small>
				</p>

				<div class="row col-12 mt-4">

					<div class="col-md-3">
						<div class="card">
							<div class="card-header p-3 shadow bg-white">
								<img src="images/products/prd5.jpg" alt="Image 1">
							</div>
							<div class="card-body">
								<div class="grid-info text-center">
									<h5 class="text-center">Hydac Dewatering and Other Fluid Conditioning</h5>
									<a href="{{ route('sub-products') }}" class="btn btn-warning btn-sm">View Products</a>
								</div>
							</div>
						</div>

					</div>
					<div class="col-md-3">
						<div class="card">
							<div class="card-header p-3 shadow bg-white">
								<img src="images/products/prd3.jpg" alt="Image 1">
							</div>
							<div class="card-body">
								<div class="grid-info text-center">
									<h5 class="text-center">Hydac Process Filters</h5>
									<a href="{{ route('sub-products') }}" class="btn btn-warning btn-sm">View Products</a>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="card">
							<div class="card-header p-3 shadow bg-white">
								<img src="images/products/prd2.jpg" alt="Image 1">
							</div>
							<div class="card-body">
								<div class="grid-info text-center">
									<h5 class="text-center">Hydac Filtering Elements</h5>
									<a href="{{ route('sub-products') }}" class="btn btn-warning btn-sm">View Products</a>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="card">
							<div class="card-header p-3 shadow bg-white">
								<img src="images/products/prd4.jpg" alt="Image 1">
							</div>
							<div class="card-body">
								<div class="grid-info text-center">
									<h5 class="text-center">Hydac Hydraulic and Lubrication Filter</h5>
									<a href="{{ route('sub-products') }}" class="btn btn-warning btn-sm">View Products</a>
								</div>
							</div>
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