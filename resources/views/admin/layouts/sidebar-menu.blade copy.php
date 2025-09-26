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
    
    @if (auth()->user()->has_access_to_pages_module())
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

    @if (auth()->user()->has_access_to_albums_module())
        <li class="nav-item with-sub @if (request()->routeIs('albums*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="image"></i> <span>Desktop Banners</span></a>
            <ul>
                <li @if (url()->current() == route('albums.edit', 1)) class="active" @endif><a href="{{ route('albums.edit', 1) }}">Manage Home Banner</a></li>
                <li @if (\Route::current()->getName() == 'albums.index' || (\Route::current()->getName() == 'albums.edit' && url()->current() != route('albums.edit', 1))) class="active" @endif><a href="{{ route('albums.index') }}">Manage Subpage Banners</a></li>
                @if(auth()->user()->has_access_to_route('albums.create'))
                    <li @if (\Route::current()->getName() == 'albums.create') class="active" @endif><a href="{{ route('albums.create') }}">Create an Album</a></li>
                @endif
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_albums_module())
        <li class="nav-item with-sub @if (request()->routeIs('mobile-albums*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="image"></i> <span>Mobile Banners</span></a>
            <ul>
                <li @if (url()->current() == route('mobile-albums.edit', 1)) class="active" @endif><a href="{{ route('mobile-albums.edit', 1) }}">Manage Home Banner</a></li>
                <li @if (\Route::current()->getName() == 'mobile-albums.index' || (\Route::current()->getName() == 'mobile-albums.edit' && url()->current() != route('mobile-albums.edit', 1))) class="active" @endif><a href="{{ route('mobile-albums.index') }}">Manage Subpage Banners</a></li>
                @if(auth()->user()->has_access_to_route('mobile-albums.create'))
                    <li @if (\Route::current()->getName() == 'mobile-albums.create') class="active" @endif><a href="{{ route('mobile-albums.create') }}">Create an Album</a></li>
                @endif
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_file_manager_module())
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
    @if (auth()->user()->has_access_to_news_module() || auth()->user()->has_access_to_news_categories_module())
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
    {{-- @if (auth()->user()->is_an_admin())
        <li class="nav-item with-sub @if (request()->routeIs('users*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="users"></i> <span>Users</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'users.index' || \Route::current()->getName() == 'users.edit') class="active" @endif><a href="{{ route('users.index') }}">Manage Users</a></li>
                <li @if (\Route::current()->getName() == 'users.create') class="active" @endif><a href="{{ route('users.create') }}">Create a User</a></li>
            </ul>
        </li>
    @endif --}}
    @if (auth()->user()->is_an_admin() || auth()->user()->has_access_to('users'))
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
    @endif

    <li class="nav-label mg-t-25">E-Commerce</li>

    @if (auth()->user()->has_access_to_module('customer'))
        <li class="nav-item with-sub @if (request()->routeIs('customers*') || \Route::current()->getName() == 'customer.signup-verification')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="users"></i> <span>Customers</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'customers.index') class="active" @endif><a href="{{ route('customers.index') }}">Manage Customers</a></li>
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_module('sales_transaction'))
        <li class="nav-item with-sub @if (request()->routeIs('sales-transaction*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="users"></i> <span>Sales Transaction</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'sales-transaction.index') class="active" @endif><a href="{{ route('sales-transaction.index') }}">Manage Sales Transaction</a></li>
            </ul>
        </li>
    @endif

    @if(auth()->user()->has_access_to_module('products') || auth()->user()->has_access_to_module('product_categories') || auth()->user()->has_access_to_module('brands'))
        <li class="nav-item with-sub @if (request()->routeIs('products*') || request()->routeIs('product-categories*') || request()->routeIs('brands*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="box"></i> <span>Products</span></a>
            <ul>
                @if (auth()->user()->has_access_to_module('products'))
                    <li @if (\Route::current()->getName() == 'products.index' || \Route::current()->getName() == 'products.edit') class="active" @endif><a href="{{ route('products.index') }}">Manage Products</a></li>
                    @if(auth()->user()->has_access_to_route('products.create'))
                        <li @if (\Route::current()->getName() == 'products.create') class="active" @endif><a href="{{ route('products.create') }}">Create a Product</a></li>
                    @endif
                @endif
                @if (auth()->user()->has_access_to_module('product_categories'))
                    <li @if (\Route::current()->getName() == 'product-categories.index' || \Route::current()->getName() == 'product-categories.edit') class="active" @endif><a href="{{ route('product-categories.index') }}">Manage Categories</a></li>
                    @if(auth()->user()->has_access_to_route('product-categories.create'))
                        <li @if (\Route::current()->getName() == 'product-categories.create') class="active" @endif><a href="{{ route('product-categories.create') }}">Create a Category</a></li>
                    @endif
                @endif
                @if (auth()->user()->has_access_to_module('product-review'))
                    <li @if (\Route::current()->getName() == 'product-review.index') class="active" @endif><a href="{{ route('product-review.index') }}">Product Reviews</a></li>
                @endif


                {{--<li @if (\Route::current()->getName() == 'product-attributes.index' || \Route::current()->getName() == 'product-attributes.edit') class="active" @endif><a href="{{ route('product-attributes.index') }}">Manage Attibutes</a></li>
                
                <li @if (\Route::current()->getName() == 'product-attributes.create') class="active" @endif><a href="{{ route('product-attributes.create') }}">Create Attibute</a></li>--}}
            </ul>
        </li>
    @endif

    @if(auth()->user()->has_access_to_module('inventory'))
        <li class="nav-item with-sub @if (request()->routeIs('inventory*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="users"></i> <span>Inventory</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'inventory.index') class="active" @endif><a href="{{ route('inventory.index') }}">Manage Inventory</a></li>
            </ul>
        </li>
    @endif

    @if(auth()->user()->has_access_to_module('promos'))
        {{--<li class="nav-item with-sub @if (request()->routeIs('promos*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="users"></i> <span>Promos</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'promos.index'|| \Route::current()->getName() == 'promos.edit') class="active" @endif><a href="{{ route('promos.index') }}">Manage Promos</a></li>
                <li @if (\Route::current()->getName() == 'promos.create') class="active" @endif><a href="{{ route('promos.create') }}">Create a Promo</a></li>
            </ul>
        </li>--}}
    @endif

    @if (auth()->user()->has_access_to_module('delivery_flat_rate'))
        {{--<li class="nav-item with-sub @if (request()->routeIs('locations*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="box"></i> <span>Delivery Flat Rates</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'locations.index' || \Route::current()->getName() == 'locations.edit') class="active" @endif><a href="{{ route('locations.index') }}">Manage Flat Rates</a></li>
                @if (auth()->user()->has_access_to_route('locations.create'))
                    <li @if (\Route::current()->getName() == 'locations.create') class="active" @endif><a href="{{ route('locations.create') }}">Create New Flat Rate</a></li>
                @endif
            </ul>
        </li>--}}
    @endif

    @if (auth()->user()->has_access_to_module('coupons'))
        <li class="nav-item with-sub @if (request()->routeIs('coupons*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="users"></i> <span>Coupons</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'coupons.index' || \Route::current()->getName() == 'coupons.edit') class="active" @endif><a href="{{ route('coupons.index') }}">Manage Coupons</a></li>
                <li @if (\Route::current()->getName() == 'coupons.create') class="active" @endif><a href="{{ route('coupons.create') }}">Create a Coupon</a></li>
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_module('banner_ads'))
        <li class="nav-item with-sub @if (request()->routeIs('ads*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="image"></i> <span>Banner Ads</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'ads.index' || \Route::current()->getName() == 'ads.edit') class="active" @endif><a href="{{ route('ads.index') }}">Manage Ads</a></li>
                <li @if (\Route::current()->getName() == 'ads.create') class="active" @endif><a href="{{ route('ads.create') }}">Create Ad</a></li>
            </ul>
        </li>
    @endif

    
    {{-- <li class="nav-item with-sub @if (request()->routeIs('mailing-list*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="credit-card"></i> <span>Mailing List</span></a>
        <ul>
            @if (auth()->user()->has_access_to_subscriber_module())
                <li @if (\Route::current()->getName() == 'mailing-list.subscribers.index' || \Route::current()->getName() == 'mailing-list.subscribers.edit') class="active" @endif><a href="{{ route('mailing-list.subscribers.index') }}">Manage Subscribers</a></li>
                @if(auth()->user()->has_access_to_route('mailing-list.subscribers.create'))
                    <li @if (\Route::current()->getName() == 'mailing-list.subscribers.create') class="active" @endif><a href="{{ route('mailing-list.subscribers.create') }}">Create a Subscriber</a></li>
                @endif
                <li @if (\Route::current()->getName() == 'mailing-list.subscribers.unsubscribe') class="active" @endif><a href="{{ route('mailing-list.subscribers.unsubscribe') }}">Cancelled Subscription</a></li>
            @endif

            @if (auth()->user()->has_access_to_subscriber_group_module())
                <li @if (\Route::current()->getName() == 'mailing-list.groups.index' || \Route::current()->getName() == 'mailing-list.groups.edit') class="active" @endif><a href="{{ route('mailing-list.groups.index') }}">Manage Groups</a></li>
                @if(auth()->user()->has_access_to_route('mailing-list.groups.create'))
                    <li @if (\Route::current()->getName() == 'mailing-list.groups.create') class="active" @endif><a href="{{ route('mailing-list.groups.create') }}">Create a Group</a></li>
                @endif
            @endif
            
            @if (auth()->user()->has_access_to_campaign_module())
                <li @if (\Route::current()->getName() == 'mailing-list.campaigns.index' || \Route::current()->getName() == 'mailing-list.campaigns.edit') class="active" @endif><a href="{{ route('mailing-list.campaigns.index') }}">Manage Campaigns</a></li>
                @if(auth()->user()->has_access_to_route('mailing-list.campaigns.create'))
                    <li @if (\Route::current()->getName() == 'mailing-list.campaigns.create') class="active" @endif><a href="{{ route('mailing-list.campaigns.create') }}">Create a Campaign</a></li>
                @endif
            @endif

            @if(auth()->user()->has_access_to_mailing_list_sent_items_module())
                <li @if (\Route::current()->getName() == 'mailing-list.campaigns.sent-campaigns') class="active" @endif><a href="{{ route('mailing-list.campaigns.sent-campaigns') }}">Sent Items</a></li>
            @endif
        </ul>
    </li> --}}

    <li class="nav-item with-sub @if (request()->routeIs('page-modals*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="edit"></i> <span>Page Modals</span></a>
        <ul>

            @if(auth()->user()->has_access_to_route('page-modals.index'))
                <li @if (\Route::current()->getName() == 'page-modals.index' || \Route::current()->getName() == 'page-modals.edit') class="active" @endif><a href="{{ route('page-modals.index') }}">Manage Modals</a></li>
            @endif

            @if(auth()->user()->has_access_to_route('page-modals.create'))
                <li @if (\Route::current()->getName() == 'page-modals.create') class="active" @endif><a href="{{ route('page-modals.create') }}">Create Modal</a></li>
            @endif
        </ul>
    </li>

    <li class="nav-label mg-t-25">Reports</li>

    <li class="nav-item @if (\Route::current()->getName() == 'report.product-list')) active show @endif">
        <a href="{{ route('report.product-list') }}" class="nav-link" target="_blank"><i data-feather="file"></i> <span>Product List</span></a>
    </li>
    {{--<li class="nav-item @if (\Route::current()->getName() == 'report.customer-list')) active show @endif">
        <a href="{{ route('report.customer-list') }}" class="nav-link" target="_blank"><i data-feather="file"></i> <span>Customer List</span></a>
    </li>
    <li class="nav-item @if (\Route::current()->getName() == 'report.promo-list')) active show @endif">
        <a href="{{ route('report.promo-list') }}" class="nav-link" target="_blank"><i data-feather="file"></i> <span>Promo List</span></a>
    </li>
    <li class="nav-item @if (\Route::current()->getName() == 'report.payment-list')) active show @endif">
        <a href="{{ route('report.payment-list') }}" class="nav-link" target="_blank"><i data-feather="file"></i> <span>Payments</span></a>
    </li>--}}
</ul>
