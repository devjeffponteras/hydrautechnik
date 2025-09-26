<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Facades\App\Helpers\ListingHelper;

use App\Models\{ResourceCategory, Resource};

use Auth, Storage;

class ResourceController extends Controller
{
    private $searchFields = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = ListingHelper::simple_search(Resource::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.resources.index', compact('resources', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ResourceCategory::where('status', 'Active')->orderBy('name', 'asc')->get();

        return view('admin.resources.create', compact('categories'));
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
            'file' => 'nullable|mimes:pdf',
        ])->validate();

        $requestData = $request->all();
        $requestData['status'] = ($request->has('status') ? 'Active' : 'Inactive');
        // $requestData['status'] = 'Active';
        $requestData['sector'] = $request->sector;
        $requestData['case_type'] = $request->case_type;
        $requestData['publish_date'] = $request->publish_date;
        $requestData['user_id'] = Auth::id();

        $requestData['slug'] = $request->name;
        $requestData['category_id'] = $request->category_id;

        $folder = 'pdf-resources';
        if($request->hasFile('file')){
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();

            $url = Storage::disk('public')->putFileAs($folder, $file, $filename);
            
            $requestData['pdf_path'] = $url;
        }
        // dd($requestData);

        Resource::create($requestData);

        return redirect(route('resources.index'))->with('success', 'Case has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        $categories = ResourceCategory::where('status', 'Active')->orderBy('name', 'asc')->get();

        return view('admin.resources.edit', compact('resource', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {
        Validator::make($request->all(), [
            'file' => 'nullable|mimes:pdf',
        ])->validate();

        $requestData = $request->all();
        $requestData['status'] = ($request->has('status') ? 'Active' : 'Inactive'); 
        $requestData['sector'] = $request->sector;
        $requestData['case_type'] = $request->case_type;
        $requestData['publish_date'] = $request->publish_date;
        $requestData['category_id'] = $request->category_id;
        $requestData['user_id'] = Auth::id();


        $folder = 'pdf-resources';
        if($request->hasFile('file')){
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();

            $url = Storage::disk('public')->putFileAs($folder, $file, $filename);
            
            $requestData['pdf_path'] = $url;
        }

        $resource->update($requestData);

        return redirect(route('resources.index'))->with('success', 'Case details has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        //
    }

    public function update_status($id,$status)
    {
        Resource::where('id',$id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.resources.update_status_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $category = Resource::findOrFail($request->categories);
        $category->update([ 'user_id' => Auth::id() ]);
        $category->delete();

        return back()->with('success', 'Resource has been deleted.');
    }

    public function restore($resourceId)
    {
        Resource::withTrashed()->find($resourceId)->update(['user_id' => Auth::id()]);
        Resource::whereId($resourceId)->restore();

        return back()->with('success', 'Case has been restored.');
    }

    public function multiple_change_status(Request $request)
    {
        $categories = explode("|", $request->categories);

        foreach ($categories as $category) {
            $publish = Resource::where('status', '!=', $request->status)->whereId($category)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success', __('standard.resources.update_status_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        // dd($request);
        $categories = explode("|",$request->categories);

        foreach($categories as $category){
            Resource::whereId((int) $category)->update(['user_id' => Auth::id() ]);
            Resource::whereId((int) $category)->delete();
        }

        return back()->with('success', __('standard.resources.multiple_delete_success'));
    }

    public function remove_file(Request $request)
    {
        $category = Resource::findOrFail($request->resourceId);
        $category->update([ 'pdf_path' => NULL ]);

        return back()->with('success', 'Case file has been removed.');
    }

    public function category_create()
    {
        Session::put('menuName', 'cases');

        return view('admin.resources.category.create');
    }
}
