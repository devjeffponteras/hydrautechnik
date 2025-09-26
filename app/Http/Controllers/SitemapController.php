<?php

namespace App\Http\Controllers;

use App\Models\{Page, ArticleCategory};
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = new Page();
        $page->name = "Sitemap";

        $breadcrumb = $this->breadcrumb($page);

        $customPages = Page::where('name', '<>', 'footer')->where('status', 'PUBLISHED')->where('parent_page_id', 0)->orderBy('id','asc')->get();
        
        $articleCategories = ArticleCategory::with('articles')->get();


        return view('theme.sitemap', compact(
            'page', 
            'breadcrumb', 
            'articleCategories', 
            'customPages'
        ));

        // return response()->view('theme.sitemap', [
        //     'pages' => $pages,
        //     'articleCategories' => $articleCategories,
        //     'page'
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sitemap  $sitemap
     * @return \Illuminate\Http\Response
     */
    public function show(Sitemap $sitemap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sitemap  $sitemap
     * @return \Illuminate\Http\Response
     */
    public function edit(Sitemap $sitemap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sitemap  $sitemap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sitemap $sitemap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sitemap  $sitemap
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sitemap $sitemap)
    {
        //
    }

    public function breadcrumb($page)
    {
        return [
            'Home' => url('/'),
            $page->name => url('/').'/'.$page->slug
        ];
    }
}
