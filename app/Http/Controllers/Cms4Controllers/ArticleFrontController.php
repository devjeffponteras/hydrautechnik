<?php

namespace App\Http\Controllers\Cms4Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests\Front\ShareEmailRequest;
use App\Helpers\Setting;
use App\Mail\ShareNewsMail;

use App\Models\Article;
use App\Models\Page;
use App\Models\User;
use App\Models\Menu;
use App\Models\Ecommerce\{BannerAd, BannerAdPage};

use Response;
use Storage;
use Auth;
use DB;

class ArticleFrontController extends Controller
{

    public function view($slug)
    {
        if(Auth::guest()) {
            $article = Article::where('slug',$slug)->whereStatus('Published')->first();
        } else {
            $article = Article::where('slug',$slug)->first();
        }

        if (!$article) {
            abort(404);
        }

        $breadcrumb = $this->breadcrumb($article);

        return view('theme.main',compact('page','breadcrumb'));

    }

    public function news_list(Request $request)
    {
        $pageLimit = 6;

        $type = $request->get('type', false);
        $criteria = $request->get('criteria', false);
        $sortby = $request->get('sortby', false);
        $year = $request->get('year', false);

        //reset session
        session()->forget('search_value');

        /* Search Function */
        if(isset($_GET['type'])){

            if($_GET['type'] == 'searchbox'){

                if(isset($request->keyword)){
                    $articles = Article::where(function($query){
                        $query->where('name','like','%'.$_GET['keyword'].'%');
                    })->whereStatus('Published');

                } else {
                    $articles = Article::where(function($query){
                        $query->where('name','like','%'.$_GET['criteria'].'%')
                        ->orWhere('contents','like','%'.$_GET['criteria'].'%');
                    })->whereStatus('Published'); 
                }
                
                session(['search_value' => $_GET['criteria']]);

            } 
            elseif($_GET['type'] == 'category'){
                if($_GET['criteria'] == 0)
                    $articles = Article::where(function($query){
                                    $query->whereNull('category_id')->orWhere('category_id','=',0);
                                })
                                ->whereStatus('Published');
                else
                    $articles = Article::where('category_id','=',$_GET['criteria'])->whereStatus('Published');
            } 
            elseif($_GET['type'] == 'month'){
                list($year, $month) = explode('-', $_GET['criteria']);

                $articles = Article::whereYear('date', '=', $year)
                                    ->whereMonth('date', '=', $month)
                                    ->whereStatus('Published');
            } 
            elseif($_GET['type'] == 'archives'){
                $articles = Article::whereYear('date', $year)->whereStatus('Published');
            } 
            else {
                $articles = Article::whereStatus('Published');
            }

            $articles = $articles->orderBy('date', 'desc')->paginate($pageLimit);
            
            $totalSearchedArticle = $articles->count();

            if($criteria){
                $categories = $this->categories($_GET['criteria']);
            } else {
                $categories = $this->categories(0);
            }           
            
        } else {
            $articles = Article::whereStatus('Published');

            if($sortby){
                $sort = explode('-', $request->sortby);
                $articles->orderBy($sort[0],$sort[1]);
            }
            else{
                $articles->orderBy('date','desc');
            }

            $articles = $articles->paginate($pageLimit);

            $totalSearchedArticle = $articles->count();
            
            $categories = $this->categories(0);
        }



        /* End Search function */
        $dates = $this->dates();
        $breadcrumb = $this->breadcrumb();

        $page = Page::where('slug', 'news')->first();
        $search = ($request->has('criteria')) ? $request->criteria : "";

        $latestArticles = Article::whereStatus('Published')->orderBy('date', 'desc')->take(5)->get();
        
        //FOR BANNER ADS
        $used_page = BannerAdPage::where('page_id', 4)->first();
        $banner_ads = BannerAd::where('id', $used_page->banner_ad_id ?? 0)->where('status', 1)->where('expiration_date', '>', now())->get();
        //END BANNER ADS

        return view('theme.pages.news-list',compact('page', 'articles','breadcrumb','categories', 'dates', 'search','totalSearchedArticle', 'latestArticles', 'type', 'criteria', 'banner_ads'))->withShortcodes();
    }

    public function dates($conditions=null) {

        if($conditions){

        }
        else{
            $years = DB::select('SELECT year(date) as yr,count(id) as total_articles FROM `articles`  where deleted_at is null and status="Published" GROUP by year(date) ORDER BY year(date)');

            $data = '<ul class="mb-0 pb-0">';

            $row = 0;
            foreach($years as $year){
                $r = $row++;
                $data .= '
                <li>
                    <a href="javascript:void(0)"><div>'.$year->yr.'</div></a>
                    <ul>';

                    $months = DB::select('SELECT year(date) as yr,month(date) as mo,count(id) as total_articles FROM `articles` WHERE year(date)="'.$year->yr.'" and deleted_at is null and status="Published" GROUP by year(date),month(date) ORDER BY month(date)');

                    foreach($months as $month){
                        $data .= '<li><a class="menu-link" href="'.route('news.front.index').'?type=month&criteria='.$year->yr.'-'.$month->mo.'"><div>'.date("F", mktime(0, 0, 0, $month->mo, 1)).' ('.$month->total_articles.')</div></a></li>';
                    }

                    $data .= '</ul>
                </li>';
            }

            $data .= '</ul>';
        }

        return $data;
    }

    public function categories($criteria){

        $categories = DB::select('SELECT ifnull(c.name, "Uncategorized") as cat, ifnull(c.id,0) as cid,count(ifnull(c.id,0)) as total_articles FROM `articles` a left join article_categories c on c.id=a.category_id where a.deleted_at is null and status="Published" GROUP BY c.name,c.id ORDER BY c.id');

        $data = '<ul class="mb-0 pb-0">';
                    foreach($categories as $category){

                        if($category->cid == $criteria){
                            $current = "current";
                        } else {
                            $current = "";
                        }

                        $data .= '<li class="'.$current.'"><a href="'.route('news.front.index').'?type=category&criteria='.$category->cid.'"><div>'.$category->cat.' ('.$category->total_articles.')</div></a></li>';
                    }

        $data .='</ul>';

        return $data;
    }


    public function breadcrumb($article = null){

        $crumbs = ['Home' => route('home')];
        $crumbs['News'] = route('news.front.index');

        if($article) {
            $article = Article::whereId((int) $article)->first();
            $crumbs[$article->name] = route('news.front.show',$article->slug);
        }

        return $crumbs;

    }

    public function filter(Request $request){

        $conditions['type'] = $request->type;
        $conditions['criteria'] = $request->criteria;
        $conditions['year'] = $request->year;

        return $this->news_list($conditions);

    }

    public function news_view($slug){

        if(auth()->guest()) {
            $news = Article::where('slug','=',$slug)->whereStatus('Published')->first();
        } else {
            $news = Article::where('slug','=',$slug)->first();
        }

        if (!$news) {
            abort(404);
        }

        $latestArticles = Article::where('id', '<>', $news->id)->whereStatus('Published')->orderBy('date', 'desc')->take(5)->get();
        $breadcrumb = $this->breadcrumb($news->id);

        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();
        $page = $news;

        $categories = $this->categories($news->category_id);
        $dates = $this->dates();

        return view('theme.pages.news',compact('footer', 'news', 'latestArticles', 'breadcrumb', 'page','categories', 'dates'));
    }

    public function news_print($slug){

        $news = Article::where('slug',$slug)->whereStatus('Published')->first();
        $page = $news;
        if (!$news) {
            abort(404);
        }

        return view('theme.pages.news-print',compact('news', 'page'));

    }

    public function news_share($slug) {
        $news = Article::where('slug', $slug)->whereStatus('Published')->first();

        if (!$news) {
            return ['status' => 'failed'];
        }

        Mail::to(request()->email_to)->send(new ShareNewsMail(Setting::info(), $news, request()->email_from, request()->sender_name, request()->name));

        if (Mail::failures()) {
            return response()->json(['status' => 'failed', 404]);
        }

        return ['status' => 'success'];
    }

}
