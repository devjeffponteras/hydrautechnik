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
        // condition here? by jeff p.

        return $this->page('home');
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
            'customPages'
        ));
    }

    public function seach_result(Request $request)
    {
        // dd($request->searchtxt);
        $page = new Page();
        $page->name = 'Search Results';

        $breadcrumb = $this->breadcrumb($page);
        $pageLimit = 10;

        $searchtxt = $request->searchtxt; 
        session(['searchtxt' => $searchtxt]);

        $pages = Page::where('status', 'PUBLISHED')
            ->whereNotIn('slug', ['footer', 'home'])
            ->where(function ($query) use ($searchtxt) {
                $query->where('name', 'like', '%' . $searchtxt . '%')
                    ->orWhere('contents', 'like', '%' . $searchtxt . '%');
            })
            ->select('name', 'slug')
            ->orderBy('name', 'asc')
            ->get();

        $news = Article::where('status', 'PUBLISHED')
            ->where(function ($query) use ($searchtxt) {
                $query->where('name', 'like', '%' . $searchtxt . '%')
                    ->orWhere('contents', 'like', '%' . $searchtxt . '%');
            })
            ->select('name', 'slug')
            ->orderBy('name', 'asc')
            ->get();

        // $products = Product::where('status', 'PUBLISHED')
        //     ->whereRaw('LOWER(book_type) NOT IN (?, ?)', ['ebook', 'e-book'])
        //     ->where(function ($query) use ($searchtxt) {
        //         $query->where('name', 'like', '%' . $searchtxt . '%')
        //         ->orWhere('author', 'like', '%' . $searchtxt . '%');
        //     })
        //     // ->select('name', "book-details/".'slug')
        //     ->select('name', DB::raw("CONCAT('book-details/', slug) as slug"))
        //     ->orderBy('name', 'asc')
        //     ->get();

        // $products = Product::select('products.*')->leftJoin('product_additional_infos', 'products.id', '=', 'product_additional_infos.product_id')
        // ->where('products.status', 'PUBLISHED')->get();

        $totalItems = $pages->count()+$news->count();

        $searchResult = collect($pages)->merge($news)->paginate(10);

        return view('theme.pages.search-result', compact('searchResult', 'totalItems', 'page','breadcrumb'));
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
}
