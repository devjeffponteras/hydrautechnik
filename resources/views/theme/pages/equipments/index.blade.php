@extends('theme.main')

@section('pagecss')
<style>
/* Compact pagination styling for equipments page */
.pagination {
	display: inline-flex;
	padding-left: 0;
	margin: 0;
	list-style: none;
}
.pagination .page-item { margin: 0 .18rem; }
.pagination .page-link {
	color: #0b2e4a;
	background: #fff;
	border: 1px solid #e9ecef;
	padding: .38rem .62rem;
	font-size: .95rem;
	border-radius: .35rem;
}
.pagination .page-link:hover { background: #f1f5f8; color: #0b2e4a; }
.pagination .page-item.active .page-link {
	background-color: #163a5b;
	border-color: #163a5b;
	color: #fff;
}
.pagination .page-item.disabled .page-link { color: #6c757d; pointer-events: none; background: transparent; border-color: transparent; }

@media (max-width: 576px) {
	.pagination .page-link { padding: .28rem .48rem; font-size: .88rem; }
}


</style>
@endsection

@php
	// Ensure $contents exists like other pages (some templates use this variable)
	$contents = $page->contents ?? '';
@endphp

@section('content')
<div class="container-fluid p-2">
	{!! $contents !!}
</div>
@endsection

@section('pagejs')
<script></script>
@endsection
