<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\ListingHelper;
use App\Http\Requests\ProductReviewRequest;

use App\Models\Ecommerce\{
    ProductReview
};


//for delete
use App\Models\Ecommerce\{
    ProductCategory, ProductPhoto, ProductTag, Product, InventoryReceiverHeader, InventoryReceiverDetail, Cart, FormAttribute, ProductAdditionalInfo, 
};

use App\Models\{
    Permission, Page, Brand, BrandProductCategory
};

class ProductReviewController extends Controller
{
    // private $searchFields = ['name'];
    // private $advanceSearchFields = ['category_id', 'code', 'name', 'brand_id', 'short_description', 'description', 'status', 'price1', 'price2', 'user_id', 'updated_at1', 'updated_at2'];
    
    // public function index()
    // {
    //     $product_reviews = ListingHelper::simple_search(ProductReview::class, $this->searchFields);
    //     // $listing = ListingHelper::required_condition('status', '!=', 'UNEDITABLE');
    //     // $products = $listing->simple_search(Product::class, $this->searchFields);

    //     // Simple search init data
    //     $filter = ListingHelper::get_filter($this->searchFields);
    //     $searchType = 'simple_search';

    //     // $advanceSearchData = ListingHelper::get_search_data($this->advanceSearchFields);
    //     // $uniqueProductByCategory = ListingHelper::get_unique_item_by_column(ProductReview::class, 'category_id');

    //     // $uniqueProductByUser = ListingHelper::get_unique_item_by_column(Product::class, 'created_by');


    //     return view('admin.ecommerce.product-review.index',compact('product_reviews', 'filter', 'searchType'));
    //     // return view('admin.ecommerce.product-review.index',compact('product_reviews', 'filter', 'searchType', 'advanceSearchData', 'uniqueProductByCategory', 'uniqueProductByUser'));

    // }

    
    private $searchFields = ['product_name','rating'];

    public function index($param = null)
    {
        $listing = new ListingHelper('asc', 10, 'product_name');

        $reviews = $listing->simple_search(ProductReview::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.ecommerce.product-review.index',compact('reviews', 'filter', 'searchType'));

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductReviewRequest $request)
    {
        $newData = $request->validated();

        $newData['product_id'] = $request->product_id;
        $newData['product_name'] = $request->product_name;
        $newData['user_id'] = $request->user_id;
        $newData['name'] = $request->name;
        $newData['email'] = $request->email;
        $newData['comment'] = $request->comment;
        $newData['rating'] = $request->rating;

        ProductReview::create($newData);

        return redirect()->back()->with('success', 'Success! your comment is now under approval');
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
        $product_review = ProductReview::findOrFail($id);

        $updateData['comment'] = $request->comment;

        $product_review->update($updateData);

        return redirect()->back()->with('success', 'Successfully edited a review');
    }

    public function update_review(Request $request)
    {
        $review = ProductReview::findOrFail($request->reviews)->update([
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Successfully edited a review');
    }
    
    public function single_approve(Request $request)
    {
        $review = ProductReview::findOrFail($request->reviews)->update([
            'status' => 1,
        ]);

        return back()->with('success', __('standard.product-review.single_approve_success'));
    }

    public function single_delete(Request $request)
    {
        $review = ProductReview::findOrFail($request->reviews);
        $review->delete();

        return back()->with('success', __('standard.product-review.single_delete_success'));
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

    public function restore($id){
        $review = ProductReview::withTrashed()->find($id);
        $review->update(['user_id' => auth()->user()->id ]);
        $review->restore();

        return back()->with('success', __('standard.product-review.restore_success'));

    }
    
    public function multiple_delete(Request $request)
    {
        $reviews = explode("|",$request->reviews);

        foreach($reviews as $review){
            ProductReview::whereId((int) $review)->delete();
        }

        return back()->with('success', __('standard.product-review.multiple_delete_success'));
    }

    public function multiple_approve(Request $request)
    {
        $reviews = explode("|",$request->reviews);

        foreach($reviews as $review){
            ProductReview::whereId((int) $review)->update([
                'status' => 1,
            ]);
        }

        return back()->with('success', __('standard.product-review.multiple_approve_success'));
    }
}
