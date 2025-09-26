@extends('theme.main')

@section('content')
<div class="container my-6">
    <div class="row">
        <div class="col-12">
            <div class="fancy-title title-border">
                <h2>Pages</h2>
            </div>
            <ul class="ms-4">
                @foreach($customPages as $cpage)
                    <li><a href="{{url('/')}}/{{$cpage->slug}}" style="color:#2ba6cb;">{{$cpage->label}}</a>
                        @if(count($cpage->sub_pages))
                            @include('theme.sitemap-subpages',['subPages' => $cpage->sub_pages])
                        @endif
                    </li>
                @endforeach
            </ul>
            
            <div class="fancy-title title-border">
                <h2>POST BY CATEGORY</h2>
            </div>
            <ul class="ms-4">
                @foreach($articleCategories as $cat)

                    @php
                        $articles = \App\Models\Article::where('category_id', $cat->id)->where('status', 'PUBLISHED')->get();
                    @endphp

                    @if(count($articles))
                        <li><a href="{{ route('news.front.index') }}?type=category&criteria={{$cat->id}}"><strong>{{$cat->name}}</strong></a>
                            <ul class="ms-4">
                                @foreach($articles as $article)
                                <li><a href="{{ route('news.front.show',$article->slug) }}" style="color:#2ba6cb;">{{$article->name}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>

        </div>
    </div>
</div>
@endsection