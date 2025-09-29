@php $page = $item->page; @endphp
@if (!empty($page) && $item->is_page_type() && $page->is_published())
    <li class="menu-item @if( url()->current() == $page->get_url() || ($page->id == 1 && url()->current() == env('APP_URL')) ) current @endif @if($item->has_sub_menus()) @endif @if(Str::contains(url()->current(), $page->get_url())) current @endif">
        <!-- for prod server -->
        <!-- <a href="{{env('APP_URL')}}/{{$page->get_url()}}" class="menu-link"> -->
        <!-- for local -->
        <a href="/{{$page->get_url()}}" class="menu-link">
            <div>
                @if (!empty($page->label))
                    {{ $page->label }} 
                @else
                    {{ $page->name }} 
                @endif
            </div>
        </a>

        {{-- @if ($item->has_sub_menus())
            <ul class="sub-menu-container">
                @foreach ($item->sub_pages as $key => $subItem)
                    {{$subItem->parent_id}}
                    @include('theme.layouts.components.menu-item', ['item' => $subItem])
                @endforeach

            </ul>
        @endif --}}

        @if ($item->has_sub_menus())
            <ul class="sub-menu-container">

                @php
                    $subItemSelect = \App\Models\MenusHasPages::where('parent_id', $item->sub_pages[0]->parent_id)->orderBy('page_order', 'asc')->get();
                @endphp

                @foreach ($subItemSelect as $subItem)
                    <!-- {{$subItem->page_order}} -->
                    @include('theme.layouts.components.menu-item', ['item' => $subItem])
                @endforeach

            </ul>
        @endif

        {{-- @if ($item->has_sub_menus())
            <ul class="sub-menu-container">

                @php
                    $sorted_items = Array();
                @endphp

                @foreach ($item->sub_pages as $subItem)
                    @php
                        array_push($sorted_items, $subItem);
                    @endphp
                @endforeach
                
                @php
                    asort($sorted_items);
                @endphp

                @foreach ($sorted_items as $sorted_item)
                    @include('theme.layouts.components.menu-item', ['item' => $sorted_item])
                @endforeach

            </ul>
        @endif --}}

    </li>

@elseif ($item->is_external_type())
    <li class="menu-item {{ Str::contains(url()->current(), $item->uri) ? 'current' : '' }}">
        <a href="{{ $item->uri }}" class="menu-link" target="{{ $item->target }}"><div>{{ $item->label }}</div></a>
        {{-- <a href="{{ env('APP_URL')."/".$item->uri }}" class="menu-link" target="{{ $item->target }}"><div>{{ $item->label }}</div></a> --}}

        @if ($item->has_sub_menus())
            <ul class="sub-menu-container">
                @php
                    $subItemSelect = \App\Models\MenusHasPages::where('parent_id', $item->sub_pages[0]->parent_id)->orderBy('page_order', 'asc')->get();
                @endphp
                
                @foreach ($subItemSelect as $subItem)
                    @include('theme.layouts.components.menu-item', ['item' => $subItem])
                @endforeach
            </ul>
        @endif
         
    </li>
@endif
