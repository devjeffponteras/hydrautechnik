
@php
    $menu = Menu::where('is_active', 1)->first();
@endphp


<ul class="menu-container">
    @foreach ($menu->parent_navigation() as $item)
        @include('theme.layouts.components.menu-item', ['item' => $item])
    @endforeach

    <!-- small screen call us btn -->
    <a href="#" class="call-us-wide-btn-sm btn btn-sm btn-warning text-dark d-flex align-items-center mb-2 gap-2" style="font-size: 12px">
        <i class="icon-line-arrow-right mr-2" style="font-size: 12px"></i>
        Call us: {{ optional(App\Helpers\Setting::info())->mobile_no ?? '' }}
    </a>
</ul>
