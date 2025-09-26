<div id="advanceSearchModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form role="form" id="advanceFilterForm" method="GET" action="{{route('product.index.advance-search')}}">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('common.advance_search')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Code</label>
                        <div>
                            <input type="text" class="form-control input-sm" name="code" value="{{ $advanceSearchData->code }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <div>
                            <input type="text" class="form-control input-sm" name="name" value="{{ $advanceSearchData->name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Category</label>
                        <div>
                            <select name="category_id" class="form-control input-sm">
                                <option value="">- All Category -</option>
                                @foreach($uniqueProductByCategory as $pr)
                                    <option value="{{($pr->category_id) ? $pr->category_id : 0}}" @if ($advanceSearchData->category_id && $advanceSearchData->category_id == $pr->category_id) selected @endif>{{$pr->category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label class="control-label">Brand</label>
                        <div>
                            <select name="brand_id" class="form-control input-sm">
                                <option value="">- All Brands -</option>
                                @foreach($brands as $br)
                                    <option value="{{$br->name}}" @if ($advanceSearchData->brand_id && $advanceSearchData->brand_id == $br->name) selected @endif>{{$br->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label class="control-label">Last Modified by</label>
                        <div>
                            <select name="user_id" class="form-control input-sm">
                                <option value="">- All Users -</option>
                                @foreach($uniqueProductByUser as $pr)
                                    <option value="{{$pr->user_id}}" @if ($advanceSearchData->user_id == $pr->user_id) selected @endif>{{$pr->user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label class="control-label">Short Description</label>
                        <div>
                            <input type="text" class="form-control input-sm" name="short_description" value="{{ $advanceSearchData->short_description }}">
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label class="control-label">Description</label>
                        <div>
                            <input type="text" class="form-control input-sm" name="description" value="{{ $advanceSearchData->description }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Visibility</label>
                        <div>
                            <select class="form-control input-sm" name="status">
                                <option value="">- Published & Private -</option>
                                <option value="published" @if ($advanceSearchData->status == 'published') selected @endif>Published only</option>
                                <option value="private" @if ($advanceSearchData->status == 'private') selected @endif>Private only</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label" for="price1">Price (From)</label>
                                <input type="number" step="0.01" class="form-control input-sm" id="price1" name="price1" value="{{ $advanceSearchData->price1 }}">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="price2">Price (To)</label>
                                <input type="number" step="0.01" class="form-control input-sm" id="price2" name="price2" value="{{ $advanceSearchData->price2 }}" min="{{ $advanceSearchData->price2 }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label" for="updated_at1">Date Modified (From)</label>
                                <input type="date" class="form-control input-sm" id="updated_at1" name="updated_at1" value="{{ $advanceSearchData->updated_at1 }}">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="updated_at2">Date Modified (To)</label>
                                <input type="date" class="form-control input-sm" id="updated_at2" name="updated_at2" value="{{ $advanceSearchData->updated_at2 }}" min="{{ $advanceSearchData->updated_at1 }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-info" href="{{ route('pages.index') }}">Reset</a>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" value="{{__('common.search')}}" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</div>