@extends('theme.main')

@section('content')
    <div class="container content-wrap">
        <div class="row">
            <div class="col-lg-12">
                {!! Setting::info()->data_privacy_content !!}
            </div>
        </div>
    </div>
@endsection

