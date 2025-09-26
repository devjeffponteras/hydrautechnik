@extends('theme.main')

@section('content')
<div class="container content-wrap">
	<div class="row">
		<div class="col-xl-9">

            @if(count($data) > 0)
                <div class="style-msg successmsg">
                    <div class="sb-msg"><i class="icon-thumbs-up"></i><strong>Woo hoo!</strong> We found <strong>(<span>{{ count($data) }}</span>)</strong> matching results.</div>
                </div>
            @else
                <div class="style-msg2 errormsg">
                    <div class="msgtitle p-0 border-0">
                        <div class="sb-msg">
                            <i class="icon-thumbs-up"></i><strong>Uh oh</strong>! <span><strong>{{ app('request')->input('keyword') }}</strong></span> you say? Sorry, no results!
                        </div>
                    </div>
                    <div class="sb-msg">
                        <ul>
                            <li>Check the spelling of your keywords.</li>
                            <li>Try using fewer, different or more general keywords.</li>
                        </ul>
                    </div>
                </div>
            @endif
			
			
			@foreach($data as $d)
			<div class="title-block">
				<h3>
					@if($d->getTable() == 'pages')
						<a href="{{$d->get_url()}}" target="_blank">{{$d->name}}</a>
					@else
						<a href="{{ route('product.details',$d->slug) }}" target="_blank">{{$d->name}}</a>
					@endif
				</h3>
				<span>
					@if($d->getTable() == 'pages')
						<a href="{{$d->get_url()}}" target="_blank">{{$d->get_url()}}</a>
					@else
						<a href="{{ route('product.details',$d->slug) }}" target="_blank">{{$d->slug}}</a>
					@endif
				</span>
				<p class="mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($d->description), 500,"  ...") }}</p>
			</div>
			@endforeach
		</div>	

		<div class="line"></div>
        {{ $data->links('theme.layouts.pagination') }}			
	</div>
</div>
@endsection