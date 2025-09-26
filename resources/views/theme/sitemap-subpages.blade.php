@foreach($subPages as $subpage)
    <ul class="ms-4">
        <li><a href="{{url('/')}}/{{$subpage->slug}}" style="color:#2ba6cb;">{{$subpage->label}}</a>
        @if(count($subpage->sub_pages))
            @include('theme.sitemap-subpages',['subPages' => $subpage->sub_pages])
        @endif
    </ul>
@endforeach