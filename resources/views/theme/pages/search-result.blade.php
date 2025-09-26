@extends('theme.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="style-msg successmsg">
                <div class="sb-msg"><i class="icon-thumbs-up"></i><strong>Awesome!</strong> We found <strong>({{ $totalItems }})</strong> matching result.</div>
            </div>
            <hr>
            <form method="GET" action="{{route('search.result')}}">
                <div class="input-group">
                    <input type="text" name="searchtxt" class="form-control form-control-lg" placeholder="Search..." aria-label="Search" aria-describedby="button-addon2" value="{{ session('searchtxt') }}">
                    <button class="btn btn-outline-primary btn-primary" type="submit" id="button-addon2"><i class="icon-search text-white"></i></button>
                </div>
            </form>
            @foreach($searchResult as $rs)
                @php
                    if($rs->getTable() == 'articles'){
                        $link = 'news/'.$rs->slug;
                    } else {
                        $link = $rs->slug;
                    }
                @endphp
                <div>
                    <blockquote>
                        <h4 class="m-0">{{ $rs->name }}</h4>
                        <a href="{{ url('/'.$link) }}" target="_blank"><small>{{ url('/'.$link) }}</small></a>
                    </blockquote>
                </div>
            @endforeach

            <br>
            {{ $searchResult->appends(request()->input())->links('theme.layouts.pagination') }}
        </div>
    </div>
</div>
@endsection
