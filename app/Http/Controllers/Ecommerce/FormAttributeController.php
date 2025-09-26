<?php

namespace App\Http\Controllers\Ecommerce;
use App\Http\Controllers\Controller;

use App\Models\Ecommerce\{FormAttribute, ProductCategory, ProductAdditionalInfo};
use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;

class FormAttributeController extends Controller
{
    private $searchFields = ['name'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = ListingHelper::simple_search(FormAttribute::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.ecommerce.attributes.index',compact('attributes', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::orderBy('name', 'asc')->get();

        return view('admin.ecommerce.attributes.create', compact('categories'));
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
        $requestData['category_id'] = NULL;

        FormAttribute::Create($requestData);
        
        return redirect(route('product-attributes.index'))->with('success', 'Product attribute has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormAttribute  $formAttribute
     * @return \Illuminate\Http\Response
     */
    public function show(FormAttribute $formAttribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormAttribute  $formAttribute
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formAttribute = FormAttribute::find($id);

        return view('admin.ecommerce.attributes.edit', compact('formAttribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormAttribute  $formAttribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $requestData = $request->all();
        $requestData['category_id'] = NULL;

        FormAttribute::find($id)->update($requestData);
        
        return redirect(route('product-attributes.index'))->with('success', 'Product attribute has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormAttribute  $formAttribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormAttribute $formAttribute)
    {
        //
    }

    public function single_delete(Request $request)
    {
        $attr = FormAttribute::find($request->attributeId);

        ProductAdditionalInfo::where('attribute_name', $attr->name)->delete();
        $attr->delete();

        return back()->with('success', 'Product attribute has been deleted.');
    }

    public function restore($attributeId){
        
        FormAttribute::whereId((int) $attributeId)->restore();

        $attr = FormAttribute::findOrFail($attributeId);
        ProductAdditionalInfo::where('attribute_name', $attr->name)->restore();

        return back()->with('success', 'Form attribute has been restored.');
    }
}
