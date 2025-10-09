<ul class="nav nav-aside">
    <li class="nav-item">
        <a href="{{route('home')}}" target="_blank" class="nav-link">
            <i data-feather="external-link"></i>
            <span>View Website</span>
        </a>
    </li>
    <li class="nav-label mg-t-25">CMS</li>
    <li class="nav-item @if (url()->current() == route('dashboard')) active @endif">
        <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="home"></i><span>Dashboard</span></a>
    </li>

    @if (auth()->user()->has_access_to_pages_module() || auth()->user()->has_access_to('pages') || auth()->user()->role_id == '9')
        <li class="nav-item with-sub @if (request()->routeIs('pages*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="layers"></i> <span>Pages</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'pages.edit' || \Route::current()->getName() == 'pages.index' || \Route::current()->getName() == 'pages.index.advance-search') class="active" @endif><a href="{{ route('pages.index') }}">Manage Pages</a></li>
                @if(auth()->user()->has_access_to_route('pages.create'))
                <li @if (\Route::current()->getName() == 'pages.create') class="active" @endif><a href="{{ route('pages.create') }}">Create a Page</a></li>
                @endif

            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_albums_module() || auth()->user()->has_access_to('albums'))
        <li class="nav-item with-sub @if (request()->routeIs('albums*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="image"></i> <span>Banners</span></a>
            <ul>
                <li @if (url()->current() == route('albums.edit', 1)) class="active" @endif><a href="{{ route('albums.edit', 1) }}">Manage Home Banner</a></li>
                <li @if (\Route::current()->getName() == 'albums.index' || (\Route::current()->getName() == 'albums.edit' && url()->current() != route('albums.edit', 1))) class="active" @endif><a href="{{ route('albums.index') }}">Manage Subpage Banners</a></li>
                @if(auth()->user()->has_access_to_route('albums.create'))
                    <li @if (\Route::current()->getName() == 'albums.create') class="active" @endif><a href="{{ route('albums.create') }}">Create an Album</a></li>
                @endif
            </ul>
        </li>
    @endif

    <!-- @if (auth()->user()->has_access_to_albums_module() || auth()->user()->has_access_to('albums'))
        <li class="nav-item with-sub @if (request()->routeIs('mobile-albums*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="image"></i> <span>Mobile Banners</span></a>
            <ul>
                <li @if (url()->current() == route('mobile-albums.edit', 1)) class="active" @endif><a href="{{ route('mobile-albums.edit', 1) }}">Manage Popup Banners</a></li>
                <li @if (\Route::current()->getName() == 'mobile-albums.index' || (\Route::current()->getName() == 'mobile-albums.edit' && url()->current() != route('mobile-albums.edit', 1))) class="active" @endif><a href="{{ route('mobile-albums.index') }}">Manage Subpage Banners</a></li>
                @if(auth()->user()->has_access_to_route('mobile-albums.create'))
                    <li @if (\Route::current()->getName() == 'mobile-albums.create') class="active" @endif><a href="{{ route('mobile-albums.create') }}">Create an Album</a></li>
                @endif
            </ul>
        </li>
    @endif -->

    @if (auth()->user()->has_access_to_file_manager_module() || auth()->user()->has_access_to('file-manager'))
        <li class="nav-item @if (\Route::current()->getName() == 'file-manager.index') active @endif">
            <a href="{{ route('file-manager.index') }}" class="nav-link"><i data-feather="folder"></i> <span>Files</span></a>
        </li>
    @endif

    @if (auth()->user()->has_access_to_menu_module())
        <li class="nav-item with-sub @if (request()->routeIs('menus*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="menu"></i> <span>Menu</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'menus.edit' || \Route::current()->getName() == 'menus.index') class="active" @endif><a href="{{ route('menus.index') }}">Manage Menu</a></li>
                <li @if (\Route::current()->getName() == 'menus.create') class="active" @endif><a href="{{ route('menus.create') }}">Create a Menu</a></li>
            </ul>
        </li>
    @endif
    @if (auth()->user()->has_access_to_news_module() || auth()->user()->has_access_to_news_categories_module() || auth()->user()->role_id == '8')
        <li class="nav-item with-sub @if (request()->routeIs('news*') || request()->routeIs('news-categories*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="edit"></i> <span>News</span></a>
            <ul>
                @if (auth()->user()->has_access_to_news_module())
                    <li @if (\Route::current()->getName() == 'news.index' || \Route::current()->getName() == 'news.edit'  || \Route::current()->getName() == 'news.index.advance-search') class="active" @endif><a href="{{ route('news.index') }}">Manage News</a></li>
                    <li @if (\Route::current()->getName() == 'news.create') class="active" @endif><a href="{{ route('news.create') }}">Create a News</a></li>
                @endif
                @if (auth()->user()->has_access_to_news_categories_module())
                    <li @if (\Route::current()->getName() == 'news-categories.index' || \Route::current()->getName() == 'news-categories.edit') class="active" @endif><a href="{{ route('news-categories.index') }}">Manage Categories</a></li>
                    <li @if (\Route::current()->getName() == 'news-categories.create') class="active" @endif><a href="{{ route('news-categories.create') }}">Create a Category</a></li>
                @endif
            </ul>
        </li>
    @endif

    @if (auth()->user()->is_an_admin() || auth()->user()->has_access_to('settings'))
        <li class="nav-item with-sub @if (request()->routeIs('account*') || request()->routeIs('website-settings*') || request()->routeIs('audit*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="settings"></i> <span>Settings</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'account.edit') class="active" @endif><a href="{{ route('account.edit') }}">Account Settings</a></li>

                @if (auth()->user()->has_access_to_website_settings_module())
                    <li @if (\Route::current()->getName() == 'website-settings.edit') class="active" @endif><a href="{{ route('website-settings.edit') }}">Website Settings</a></li>
                @endif

                @if (auth()->user()->has_access_to_audit_logs_module())
                    <li @if (\Route::current()->getName() == 'audit-logs.index') class="active" @endif><a href="{{ route('audit-logs.index') }}">Audit Trail</a></li>
                @endif
            </ul>
        </li>
    @endif
    @if (auth()->user()->is_an_admin() || auth()->user()->has_access_to('users') || auth()->user()->role_id == '10')
        <li class="nav-item with-sub @if (request()->routeIs('users*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="users"></i> <span>Users</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'users.index' || \Route::current()->getName() == 'users.edit') class="active" @endif><a href="{{ route('users.index') }}">Manage Users</a></li>
                <li @if (\Route::current()->getName() == 'users.create') class="active" @endif><a href="{{ route('users.create') }}">Create a User</a></li>
            </ul>
        </li>
    @endif
    @if (auth()->user()->is_an_admin())
        <li class="nav-item with-sub @if (request()->routeIs('role*') || request()->routeIs('access*') || request()->routeIs('permission*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="user"></i> <span>Account Management</span></a>
            <ul>
                <li @if (request()->routeIs('role*')) class="active" @endif><a href="{{ route('role.index') }}">Roles</a></li>
                <li @if (request()->routeIs('access*')) class="active" @endif><a href="{{ route('access.index') }}">Access Rights</a></li>
                <li @if (request()->routeIs('permission*')) class="active" @endif><a href="{{ route('permission.index') }}">Permissions</a></li>
            </ul>
        </li>
        <li class="nav-item with-sub @if (request()->routeIs('products*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="package"></i> <span>Products</span></a>
            <ul>
                <li @if (request()->routeIs('products.index')) class="active" @endif>
                    <a href="{{ route('products.index') }}">Manage Products</a>
                </li>
                <li @if (request()->routeIs('products.create')) class="active" @endif>
                    <a href="{{ route('products.create') }}">Create Products</a>
                </li>
                <li @if (request()->routeIs('products.create_category')) class="active" @endif>
                    <a href="{{ route('products.create_category') }}">Create Categories</a>
                </li>
                <li @if (request()->routeIs('products.create_subcategory')) class="active" @endif>
                    <a href="{{ route('products.create_subcategory') }}">Manage Subcategories</a>
                </li>
                {{-- You can add more product-related links here --}}
            </ul>
        </li>
    @endif

</ul>
