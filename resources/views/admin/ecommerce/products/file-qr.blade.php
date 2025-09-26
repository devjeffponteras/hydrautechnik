@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .qr-code-container {
            display: flex;
            flex-direction: column; /* Ensure content is stacked vertically */
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full height of the viewport */
        }

        .qr-code {
            max-width: 100%; /* Ensure the QR code is responsive */
        }

        .product-name {
            margin-bottom: 20px; /* Space between name and QR code */
            text-align: center; /* Center align the text */
        }
    </style>
@endsection

@section('content')
    <div class="qr-code-container">
        <!-- Display the product name and description -->
        <div class="product-name">
            <h1>{{ $product->name }}</h1>
        </div>
        
        <!-- Display the QR code -->
        <div class="qr-code">
            {!! $qrCode !!}
        </div>
    </div>
@endsection

@section('pagejs')
@endsection
