<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;

use App\Http\Requests\ContactUsRequest;
use App\Helpers\Setting;

use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryAdminMail;
use App\Mail\InquiryMail;

use App\Models\Article;
use App\Models\Page;
use App\Models\User;

use App\Models\ResourceCategory;
use App\Models\Resource;
use App\Models\TemplateCategory;
use App\Models\Template;
use App\Models\EmailRecipient;
use App\Models\ArticleCategory;
use App\Models\Ecommerce\{BannerAd, BannerAdPage, Product};

use App\Models\ProductCategory;

use Auth;
use DB;
use Session;



class FrontController extends Controller
{

    public function registration()
    {
        $categories = TemplateCategory::where('status','Active')->orderBy('name','asc')->get();
        $templates  = Template::where('status','Active')->get();
        return view('theme.template-registration',compact('categories','templates'));
    }

    public function request_for_demo($id)
    {
        $template = Template::find($id);

        return view('theme.demo',compact('template'));
    }

    public function home()
    {
        // Load home page explicitly so we can pass additional data (clients)
        if (Auth::guest()) {
            $page = Page::where('slug', 'home')->where('status', 'PUBLISHED')->first();
        } else {
            $page = Page::where('slug', 'home')->first();
        }

        if ($page == null) {
            abort(404);
        }

        $breadcrumb = $this->breadcrumb($page);
        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();

        // Fetch clients to display on home carousel (only those with logos)
        $clients = \App\Models\Client::whereNotNull('logo')
            ->where('logo', '<>', '')
            ->orderBy('company')
            ->get();

        if (!empty($page->template)) {
            return view('theme.pages.' . $page->template, compact('footer', 'page', 'breadcrumb', 'clients'));
        }

        $parentPage = null;
        $parentPageName = $page->name;
        $currentPageItems = [];
        $currentPageItems[] = $page->id;
        if ($page->has_parent_page() || $page->has_sub_pages()) {
            if ($page->has_parent_page()) {
                $parentPage = $page->parent_page;
                $parentPageName = $parentPage->name;
                $currentPageItems[] = $parentPage->id;
                while ($parentPage->has_parent_page()) {
                    $parentPage = $parentPage->parent_page;
                    $currentPageItems[] = $parentPage->id;
                }
            } else {
                $parentPage = $page;
                $currentPageItems[] = $parentPage->id;
            }
        }

        return view('theme.page', compact('footer', 'page', 'parentPage', 'breadcrumb', 'currentPageItems', 'parentPageName', 'clients'));
    }

    public function privacy_policy(){

        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();

        $page = new Page();
        $page->name = 'Privacy Policy';

        $breadcrumb = $this->breadcrumb($page);

        return view('theme.pages.privacy-policy', compact('page', 'footer','breadcrumb'));

    }

    public function sitemap()
    {
        // return $this->page('sitemap');

        $page = $this->page('sitemap')->page;

        $breadcrumb = $this->breadcrumb($page);

        $customPages = Page::where('name', '<>', 'footer')->where('status', 'PUBLISHED')->where('parent_page_id', 0)->orderBy('id','asc')->get();

        $articleCategories = ArticleCategory::with('articles')->get();

        return view('theme.pages.sitemap', compact(
            'page',
            'breadcrumb',
            'articleCategories',
        ));
    }

    public function page($slug = "home")
    {

        if (Auth::guest()) {
            $page = Page::where('slug', $slug)->where('status', 'PUBLISHED')->first();
        } else {
            $page = Page::where('slug', $slug)->first();
        }

        if ($page == null) {
            $view404 = 'theme.pages.404';
            if (view()->exists($view404)) {
                $page = new Page();
                $page->name = 'Page not found';
                return view($view404, compact('page'));
            }

            abort(404);
        }
        $breadcrumb = $this->breadcrumb($page);

        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();

        if (!empty($page->template)) {
            return view('theme.pages.'.$page->template, compact('footer', 'page', 'breadcrumb'));
        }

        $parentPage = null;
        $parentPageName = $page->name;
        $currentPageItems = [];
        $currentPageItems[] = $page->id;
        if ($page->has_parent_page() || $page->has_sub_pages()) {
            if ($page->has_parent_page()) {
                $parentPage = $page->parent_page;
                $parentPageName = $parentPage->name;
                $currentPageItems[] = $parentPage->id;
                while ($parentPage->has_parent_page()) {
                    $parentPage = $parentPage->parent_page;
                    $currentPageItems[] = $parentPage->id;
                }
            } else {
                $parentPage = $page;
                $currentPageItems[] = $parentPage->id;
            }
        }

        return view('theme.page', compact('footer', 'page', 'parentPage', 'breadcrumb', 'currentPageItems', 'parentPageName'));
    }


    public function contact_us(Request $request)
    {
        // dd($request);
        $email_recipients  = EmailRecipient::all();
        $client = $request->all();

        \Mail::to($client['email'])->send(new InquiryMail(Setting::info(), $client));

        foreach ($email_recipients as $email_recipient) {
            \Mail::to($email_recipient->email)->send(new InquiryAdminMail(Setting::info(), $client, $email_recipient));
        }

        session()->flash('success', 'Email sent!');

        return redirect()->back();
    }

    // public function contact_us(ContactUsRequest $request)
    // {
    //     $admins  = User::where('role_id', 1)->get();
    //     $client = $request->all();

    //     Mail::to($client['email'])->send(new InquiryMail(Setting::info(), $client));

    //     foreach ($admins as $admin) {
    //         Mail::to($admin->email)->send(new InquiryAdminMail(Setting::info(), $client, $admin));
    //     }

    //     if (Mail::failures()) {
    //         return redirect()->back()->with('error','Failed to send inquiry. Please try again later.');
    //     }

    //     return redirect()->back()->with('success','Email sent!');
    // }

    public function breadcrumb($page)
    {
        return [
            'Home' => url('/'),
            $page->name => url('/').'/'.$page->slug
        ];
    }

    public function resource_list(Request $request)
    {
        Session::put('menuName', 'cases');

        //dd(Session::get('menuName'));
        $filterYear = $request->get('year',false);

        $page = Page::where('slug', 'cases')->first();
        $page->name = "Cases";

        $breadcrumb = $this->breadcrumb($page);

        // $years = DB::select('SELECT year(created_at) as yr FROM `resources`  where deleted_at is null and status = "Active" GROUP by year(created_at) ORDER BY year(created_at)');

        $years = DB::select('SELECT year(publish_date) as yr FROM `resources`  where deleted_at is null and status = "Active" GROUP by year(publish_date) ORDER BY year(publish_date)');


        $categories = ResourceCategory::where('status', 'Active')->get();
        $searchCategories = ResourceCategory::where('id', '<>', 3)->where('status', 'Active')->orderBy('name', 'asc')->get();


        $resources = Resource::where('status', 'Active');

        if($filterYear){
            $resources->whereYear('publish_date', $request->year);
        }

        $resources = $resources->orderBy('publish_date', 'desc')->orderBy('name', 'asc')->get();
        // dd($resources);
        $categorySlug = "Cases";
        $slug = "";
        $keyword = "";
        // dd($searchCategories);
        return view('theme.pages.resource-list', compact('page', 'resources','categories','breadcrumb', 'categorySlug', 'years', 'filterYear', 'searchCategories', 'slug', 'keyword'));
    }

    public function resource_category_list(Request $request, $slug)
    {
        Session::put('menuName', 'cases');

        $filterYear = $request->get('year',false);
        $keyword = $request->get('keyword',false);
        // dd($filterYear);
        $resourceCategory = ResourceCategory::where('slug', $slug)->first();
        $page = Page::where('slug', 'cases')->first();

        // dd($resourceCategory->id);

        $page->name = $resourceCategory->name;

        $breadcrumb = $this->breadcrumb($page);

        // $years = DB::select('SELECT year(created_at) as yr FROM `resources`  where deleted_at is null and status = "Active" GROUP by year(created_at) ORDER BY year(created_at)');

        $years = DB::select('SELECT year(publish_date) as yr FROM `resources`  where deleted_at is null and status = "Active" GROUP by year(publish_date) ORDER BY year(publish_date)');

        $resources = Resource::where('category_id', $resourceCategory->id);

        if($filterYear) {
            $resources->whereYear('publish_date', $request->year);
        }

        if($keyword) {
            $resources->where('category_id', $resourceCategory->id);
        }

        $resources = $resources->orderBy('publish_date', 'desc')->orderBy('name', 'asc')->paginate(10);
        // dd($resources);
        //$categories = ResourceCategory::where('id', '<>', 3)->where('parent_id', 0)->where('status', 'Active')->orderBy('name', 'asc')->get();
        $categories = ResourceCategory::where('status', 'Active')->get();
        $searchCategories = ResourceCategory::where('id', '<>', 3)->where('status', 'Active')->orderBy('name', 'asc')->get();
        // $searchCategories = ResourceCategory::where('id', $resourceCategory->id )->orderBy('name', 'asc')->get();

        $categorySlug = $resourceCategory->name;

        return view('theme.pages.resource-list', compact('page', 'resources','categories','breadcrumb', 'categorySlug', 'years', 'filterYear', 'searchCategories', 'keyword', 'slug'));
    }

    public function resource_details($slug)
    {
        Session::put('menuName', 'cases');

        $resource = Resource::where('slug', $slug)->first();

        $page = Page::where('slug', 'cases')->first();
        $page->name = "$resource->name";

        $breadcrumb = $this->breadcrumb($page);

        return view('theme.pages.resource-details', compact('page', 'resource','breadcrumb'));

    }

    public function portfolio() {
        $page = new Page();
        $page->name = 'Portfolio';

        return view('theme.pages.portfolio.index', compact('page'));
    }

    public function products() {
        $page = new Page();
        $page->name = 'Products';

        $mainCategories = ProductCategory::all();
        // Only get main products (exclude products with tag = 2)
        $allProducts = Product::select('products.*')
            ->where('status', 'PUBLISHED')
            ->where(function($q) {
                $q->whereNull('tag')->orWhere('tag', '!=', 2);
            })
            ->orderBy('name', 'asc')
            ->get();
        $otherProducts = Product::where('tag', 2)->where('status', 'PUBLISHED')->get();

        // Debug: Log the actual count
        \Log::info('Main Products Count: ' . $allProducts->count());
        \Log::info('Other Products Count: ' . $otherProducts->count());

        return view('theme.pages.products.index', compact('page', 'mainCategories', 'allProducts', 'otherProducts'));
    }

    // NEW METHOD: Show products filtered by category
    public function productsByCategory($categoryId) {
        $page = new Page();

        $mainCategories = ProductCategory::all();
        $otherProducts = Product::where('tag', 2)->get();

        // Get the selected category
        $selectedCategory = \App\Models\ProductCategory::find($categoryId);

        // Handle if category doesn't exist
        if (!$selectedCategory) {
            return redirect()->route('products');
        }

        $page->name = $selectedCategory->name;

        // Check if this category has subcategories
        $hasSubcategories = \App\Models\ProductSubcategory::where('category_id', $categoryId)->exists();

        if (!$hasSubcategories) {
            // If no subcategories, redirect to products list with category filter
            // This will show ONLY products directly attached to this category
            return redirect()->route('sub-products', ['category' => $categoryId]);
        }

        // Has subcategories - show the subcategories grid
        return view('theme.pages.products.index', compact('page', 'selectedCategory', 'mainCategories', 'otherProducts'));
    }

    public function subProducts(Request $request) {
        $page = new Page();
        $page->name = 'Sub Products';

        $otherProducts = Product::where('tag', 2)->where('status', 'PUBLISHED')->get();

        // Get all categories (simple list)
        $mainCategories = \App\Models\ProductCategory::getAllCategories()->get();

        // Get subcategory ID or category ID from request
        $subcategoryId = $request->get('subcategory');
        $categoryId = $request->get('category');
        $selectedSubcategory = null;
        $selectedCategory = null;

        if ($subcategoryId) {
            // Viewing products from a specific subcategory
            $selectedSubcategory = \App\Models\ProductSubcategory::find($subcategoryId);
            if ($selectedSubcategory) {
                $page->name = $selectedSubcategory->name . ' - Products';
                $selectedCategory = $selectedSubcategory->category;
            }
        } elseif ($categoryId) {
            // Viewing products from a category that has NO subcategories
            $selectedCategory = \App\Models\ProductCategory::find($categoryId);
            if ($selectedCategory) {
                $page->name = $selectedCategory->name . ' - Products';
            }
        }

        return view('theme.pages.products.sub-index', compact('page', 'mainCategories', 'selectedSubcategory', 'selectedCategory', 'otherProducts'));
    }

    public function viewProducts(Request $request, $id) {
        $page = new Page();
        $page->name = 'View Product';

        $mainCategories = ProductCategory::all();
        $otherProducts = \App\Models\Product::where('tag', 2)->where('status', 'PUBLISHED')->get();

        $product = null;
        if ($request->has('id')) {
            $product = \App\Models\Product::with(['subcategory', 'category'])->where('status', 'PUBLISHED')->find($request->id);
        } else {
            $product = \App\Models\Product::with(['subcategory', 'category'])->where('status', 'PUBLISHED')->find($id);
        }

        // If product is not found or not published, show 404
        if (!$product) {
            abort(404, 'Product not found or not available');
        }

        return view('theme.pages.products.view', compact('page', 'product', 'mainCategories', 'otherProducts'));
    }

    public function equipments() {
        $page = new Page();
        $page->name = 'Equipments';

        // fetch equipments from database, include category if needed
        // paginate public equipments listing to 5 items per page
        $equipments = \App\Models\Equipment::orderBy('name', 'asc')->paginate(5);

        return view('theme.pages.equipments.index', compact('page', 'equipments'));
    }

    public function services() {
        $page = new Page();
        $page->name = 'Company Capabilities';

        // load published services to display on the front page; paginate to 5 per page
        $services = \App\Models\Service::where('status', 'PUBLISHED')->orderBy('name', 'asc')->paginate(5);

        return view('theme.pages.services.index', compact('page', 'services'));
    }

    /**
     * Show a single service detail page.
     */
    public function serviceShow($id) {
        $page = new Page();
        $page->name = 'Company Capabilities';

        $service = \App\Models\Service::where('status', 'PUBLISHED')->find($id);
        if (!$service) {
            abort(404);
        }

        return view('theme.pages.services.show', compact('page', 'service'));
    }


    /**
     * Projects listing (front)
     */
    public function projects()
    {
        $page = new Page();
        $page->name = 'Projects';

        // load published projects
        $projects = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('projects')) {
            $projects = \App\Models\Project::where('status', 'PUBLISHED')->orderByDesc('created_at')->get();
        }

        return view('theme.pages.projects.index', compact('page', 'projects'));
    }

    /**
     * Show a single project detail page.
     */
    public function projectShow($id)
    {
        $page = new Page();
        $page->name = 'Projects';

        if (!\Illuminate\Support\Facades\Schema::hasTable('projects')) {
            abort(404);
        }

        $project = \App\Models\Project::where('status', 'PUBLISHED')->find($id);
        if (!$project) {
            abort(404);
        }

        return view('theme.pages.projects.show', compact('page', 'project'));
    }


}
