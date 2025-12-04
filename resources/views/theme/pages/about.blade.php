@extends('theme.main')

@section('pagecss')

@endsection

@php
	// Ensure $contents exists like other pages (some templates use this variable)
	$contents = $page->contents ?? '';
@endphp

@section('content')
<div class="container-fluid p-0 about-page">
	{!! $contents !!}
</div>
@endsection
