<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;

use App\Models\ResourceCategory;

use Auth;

class ResourceCategoryController extends Controller
{   
    private $searchFields = ['name'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ListingHelper::simple_search(ResourceCategory::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.resources.category.index', compact('categories', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ResourceCategory::orderBy('name', 'asc')->get();

        return view('admin.resources.category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        // $requestData['status'] = ($request->has('status') ? 'Active' : 'Inactive'); 
        $requestData['status'] = 'Active'; 
        $requestData['parent_id'] = 3; 
        $requestData['user_id'] = Auth::id();
        $requestData['slug'] = $request->name;

        ResourceCategory::create($requestData);

        return redirect(route('resource-categories.index'))->with('success', 'Cases category has been added.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cat = ResourceCategory::find($id);
        $categories = ResourceCategory::orderBy('name', 'asc')->get();
        

        return view('admin.resources.category.edit', compact('cat', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $requestData = $request->all();
        $requestData['parent_id'] = isset($request->parent_id) ? $request->parent_id : 0;
        $requestData['status'] = 'Active'; 
        $requestData['user_id'] = Auth::id();

        ResourceCategory::find($id)->update($requestData);

        return redirect(route('resource-categories.index'))->with('success', 'Resource category has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function single_delete(Request $request)
    {
        $category = ResourceCategory::findOrFail($request->categories);
        $category->update([ 'user_id' => Auth::id() ]);
        $category->delete();

        return back()->with('success', 'Resource Category has been deleted.');
    }

    public function restore($resourceId)
    {
        ResourceCategory::withTrashed()->find($resourceId)->update(['user_id' => Auth::id()]);
        ResourceCategory::whereId($resourceId)->restore();

        return back()->with('success', 'Resource Category has been restored.');
    }

    public function update_status($id,$status)
    {
        ResourceCategory::where('id',$id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.resource-categories.update_status_success', ['STATUS' => $status]));
    }

    public function multiple_change_status(Request $request)
    {
        $categories = explode("|", $request->categories);

        foreach ($categories as $category) {
            $publish = ResourceCategory::where('status', '!=', $request->status)->whereId($category)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success', __('standard.resource-categories.update_status_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $categories = explode("|",$request->categories);

        foreach($categories as $category){
            ResourceCategory::whereId($category)->update(['user_id' => Auth::id() ]);
            ResourceCategory::whereId($category)->delete();
        }

        return back()->with('success', 'Case/s has been removed on the lists.');
    }
}
