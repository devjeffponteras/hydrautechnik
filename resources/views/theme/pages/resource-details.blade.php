@extends('theme.main')

@section('content')
<div class="container my-6">
	<div class="row">
		<div class="col-12">
			
			<div class="heading-block">
				<h3>{{$resource->name}}</h3>
			</div>
			
			{!!$resource->contents!!}
			
			@if(isset($resource->pdf_path))
				@php
					$file = explode('/', $resource->pdf_path);
				@endphp
				<a href="{{ asset('storage/'.$resource->pdf_path) }}" class="text-primary" target="_blank">{{end($file)}}</a>
			@endif
			<br><br>
			<a href="{{ route('resource-list.front.show') }}" class="button rounded button-dark"><i class="icon-arrow-left"></i> Back</a>
		</div>
	</div>
</div>
@endsection