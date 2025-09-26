
@php
    $menu = Menu::where('is_active', 1)->first();
@endphp


<ul class="menu-container">
    @foreach ($menu->parent_navigation() as $item)
        @include('theme.layouts.components.menu-item', ['item' => $item])
    @endforeach
</ul>
