<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Facades\App\Helpers\ListingHelper;

use App\Models\{Page, Permission, FileDownloadCategory};
use Illuminate\Http\Request;

use Auth;

class FileDownloadCategoryController extends Controller
{
    private $searchFields = ['title'];

    public function __construct()
    {
        Permission::module_init($this, 'file_download_category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ListingHelper::simple_search(FileDownloadCategory::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.ecommerce.downloadables.category_index',compact('categories', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ecommerce.downloadables.category_create');
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
            'title' => 'required|max:150|unique:file_download_category,title',
            'type' => 'required'
        ])->validate();

        $requestData = $request->all();
        $requestData['slug'] = Page::convert_to_slug($request->title);
        $requestData['status'] = ($request->has('status') ? 'Active' : 'Inactive'); 
        $requestData['parent_category'] = 0;
        $requestData['user_id'] = Auth::id();
        
        FileDownloadCategory::create($requestData);

        return redirect(route('file-categories.index'))->with('success', 'File category has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FileDownloadCategory  $fileDownloadCategory
     * @return \Illuminate\Http\Response
     */
    public function show(FileDownloadCategory $fileDownloadCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FileDownloadCategory  $fileDownloadCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fileDownloadCategory = FileDownloadCategory::find($id);

        return view('admin.ecommerce.downloadables.category_edit', compact('fileDownloadCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FileDownloadCategory  $fileDownloadCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required|max:150',
            'type' => 'required'
        ])->validate();

        $requestData = $request->all();
        $requestData['slug'] = Page::convert_to_slug($request->title);
        $requestData['status'] = ($request->has('status') ? 'Active' : 'Inactive'); 
        $requestData['parent_category'] = 0;
        $requestData['user_id'] = Auth::id();
        
        FileDownloadCategory::find($request->id)->update($requestData);

        return redirect(route('file-categories.index'))->with('success', 'File category has been update.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FileDownloadCategory  $fileDownloadCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileDownloadCategory $fileDownloadCategory)
    {
        //
    }

    public function single_delete(Request $request)
    {
        $category = FileDownloadCategory::findOrFail($request->categories);
        $category->update([ 'user_id' => Auth::id() ]);
        $category->delete();

        return back()->with('success', 'File Category has been deleted.');
    }

    public function multiple_delete(Request $request)
    {
        $categories = explode("|",$request->categories);

        foreach($categories as $category){
            FileDownloadCategory::whereId((int) $category)->update(['user_id' => Auth::id() ]);
            FileDownloadCategory::whereId((int) $category)->delete();
        }

        return back()->with('success', __('standard.file-category.multiple_delete_success'));
    }

    public function restore($category){
        FileDownloadCategory::withTrashed()->find($category)->update(['user_id' => Auth::id() ]);
        FileDownloadCategory::whereId((int) $category)->restore();

        return back()->with('success', 'File Category has been restored.');
    }

    public function update_status($id,$status)
    {
        FileDownloadCategory::where('id',$id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.file-category.category_update_success', ['STATUS' => $status]));
    }

    public function multiple_change_status(Request $request)
    {
        $categories = explode("|", $request->categories);

        foreach ($categories as $category) {
            $publish = FileDownloadCategory::where('status', '!=', $request->status)->whereId((int) $category)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.file-category.category_update_success', ['STATUS' => $request->status]));
    }
}
