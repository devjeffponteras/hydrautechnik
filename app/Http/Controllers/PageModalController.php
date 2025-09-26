<?php

namespace App\Http\Controllers;

use App\Models\{PageModal, Page, ArticleCategory, ResourceCategory};

use Illuminate\Support\Facades\Validator;
use Facades\App\Helpers\ListingHelper;
use Illuminate\Http\Request;

use Auth;

class PageModalController extends Controller
{
    private $folder = 'admin.modals.';
    private $searchFields = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modals = ListingHelper::simple_search(PageModal::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view($this->folder.'index', compact('modals', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customPages = Page::select('name', 'slug')->where('parent_page_id', 0)->where('status', 'PUBLISHED')->get();
        $articleCategories = ArticleCategory::select('name', 'slug')->with('articles')->get();
        // $resourceCategories = ResourceCategory::select('name', 'slug')->where('status', 'Active')->get();

        $pages = array_merge($customPages->toArray(),$articleCategories->toArray());
        // $pages = array_merge($customPages->toArray(),$articleCategories->toArray(),$resourceCategories->toArray());

        // dd($customPages);

        return view($this->folder.'create', compact('pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'pages' => 'required',
            'content' => 'required',
        ])->validate();

        $requestData = $request->all();

        $pages = "";
        foreach($request->pages as $page){
            $pages .= $page.',';
        }

        $requestData['pages'] = $pages;
        $requestData['status'] = ($request->has('status') ? 'Active' : 'Inactive');

        PageModal::create($requestData);

        return redirect(route('page-modals.index'))->with('success', 'Page modal has been added.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PageModal  $pageModal
     * @return \Illuminate\Http\Response
     */
    public function show(PageModal $pageModal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PageModal  $pageModal
     * @return \Illuminate\Http\Response
     */
    public function edit(PageModal $pageModal)
    {
        $customPages = Page::select('name', 'slug')->where('parent_page_id', 0)->where('status', 'PUBLISHED')->get();
        $articleCategories = ArticleCategory::select('name', 'slug')->with('articles')->get();
        // $resourceCategories = ResourceCategory::select('name', 'slug')->where('status', 'Active')->get();

        $pages = array_merge($customPages->toArray(),$articleCategories->toArray());
        // $pages = array_merge($customPages->toArray(),$articleCategories->toArray(),$resourceCategories->toArray());

        return view($this->folder.'edit', compact('pageModal', 'pages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PageModal  $pageModal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PageModal $pageModal)
    {
         Validator::make($request->all(), [
            'name' => 'required',
            'pages' => 'required',
            'content' => 'required',
        ])->validate();

        $requestData = $request->all();

        $pages = "";
        foreach($request->pages as $page){
            $pages .= $page.',';
        }

        $requestData['pages'] = $pages;
        $requestData['status'] = ($request->has('status') ? 'Active' : 'Inactive');

        $pageModal->update($requestData);

        return redirect(route('page-modals.index'))->with('success', 'Page modal has been added.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PageModal  $pageModal
     * @return \Illuminate\Http\Response
     */
    public function destroy(PageModal $pageModal)
    {
        //
    }

    public function update_status($id,$status)
    {
        PageModal::where('id',$id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.modals.update_status_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $category = PageModal::findOrFail($request->categories);
        $category->update([ 'user_id' => Auth::id() ]);
        $category->delete();

        return back()->with('success', 'Page modal has been deleted.');
    }

    public function restore($modalId)
    {
        PageModal::withTrashed()->find($modalId)->update(['user_id' => Auth::id()]);
        PageModal::whereId((int)$modalId)->restore();

        return back()->with('success', 'Page modal has been restored.');
    }

    public function multiple_change_status(Request $request)
    {
        $categories = explode("|", $request->categories);

        foreach ($categories as $category) {
            $publish = PageModal::where('status', '!=', $request->status)->whereId((int)$category)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success', __('standard.modals.update_status_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $categories = explode("|",$request->categories);

        foreach($categories as $category){
            PageModal::whereId((int)$category)->update(['user_id' => Auth::id() ]);
            PageModal::whereId((int)$category)->delete();
        }

        return back()->with('success', __('standard.modals.multiple_delete_success'));
    }
}
