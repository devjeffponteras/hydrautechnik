@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('mailing-list.subscribers.index')}}">Manage Groups</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Group</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit Mailing Group</h4>
            </div>
        </div>

        <form method="post" action="{{ route('mailing-list.groups.update', $group->id) }}" class="row row-sm">
            @csrf
            @method('put')
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="d-block">Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $group->name) }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="d-block">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" rows="4" name="description">{{ old('description', $group->description) }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="d-block">Subscribers
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="all" name="all" class="custom-control-input" @if ($subscribers->count() == $group->subscribers->count()) checked @endif >
                            <label class="custom-control-label" for="all">All</label>
                        </div>
                    </label>
                    <select id="subscribers" name="subscribers[]" multiple="multiple" class="form-control @error('subscribers') is-invalid @enderror">
                        @foreach ($subscribers as $subscriber)
                            <option value="{{ $subscriber->id }}" @if (in_array($subscriber->id, old('subscribers', $group->subscribers->pluck('id')->toArray()))) selected @endif>{{ $subscriber->email }}</option>
                        @endforeach
                    </select>
                    @error('subscribers')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-12 mg-t-10">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Mailing Group</button>
                <a href="{{ route('mailing-list.groups.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $('#subscribers').select2({
            closeOnSelect: false,
            scrollAfterSelect: true
        });

        $('#all').on("change", function (e) {
            if ($(this).is(':checked')) {
                $("#subscribers > option").prop("selected","selected");
                $("#subscribers").trigger("change");
            }
        });
    </script>
@endsection
