@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('products.index')}}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Assignment</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Customer Assignment</h4>
            </div>
        </div>

        <form method="post" class="row row-sm" action="{{ route('product.ebook-customer-assignment-update', $product->id) }}">
            @csrf
            @method('put')
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="d-block">Product Name</label>
                    <input required name="name" id="name" value="{{ old('name',$product->name) }}" type="text" class="form-control @error('name') is-invalid @enderror" maxlength="150" readonly>
                </div>
                <div class="form-group">
                    <label class="d-block">Customers </label>
                    <select id="customers" name="customers[]" class="form-control @error('customers') is-invalid @enderror" data-style="btn btn-outline-light btn-md btn-block tx-left" multiple required>

                        @php($customer_ids = $ebook_customers->pluck('user_id')->toArray())

                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @if (in_array($customer->id, old('customers', [])) || in_array($customer->id, $customer_ids)) selected @endif>
                                {{ $customer->name }}
                            </option>
                        @endforeach

                    </select>
                    @error('customers')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-12 mg-t-10 pd-b-40">
                <input class="btn btn-primary btn-sm tx-uppercase tx-semibold" name="submit" type="submit" value="Save">
                <a  href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm tx-uppercase tx-semibold">Cancel</a>
            </div>
        </form>

    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $('#customers').select2({ closeOnSelect: false });
        $('#customers').trigger('change');
    </script>
@endsection
