@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ route('mailing-list.campaigns.index') }}">Manage Campaigns</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Campaign</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit Campaign</h4>
            </div>
        </div>

        <form method="post" class="row row-sm" action="{{ route('mailing-list.campaigns.update', $campaign->id) }}">
            @csrf
            @method('put')
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="d-block">Campaign Name *</label>
                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $campaign->name) }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="d-block">Subject *</label>
                    <input name="subject" type="text" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $campaign->subject) }}" required>
                    @error('subject')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="d-block">Recipient </label>
                    <select id="recipients" name="recipients[]" class="form-control @error('recipients') is-invalid @enderror" data-style="btn btn-outline-light btn-md btn-block tx-left" multiple>

                        @php($recipientIds = $campaign_recipients->pluck('subscriber_id')->toArray())

                        @foreach ($subscribers as $subscriber)
                            <option value="{{ $subscriber->id }}" @if (in_array($subscriber->id, old('recipients', [])) || in_array($subscriber->id, $recipientIds)) selected @endif>
                                {{ $subscriber->email_with_name() }}
                            </option>
                        @endforeach

                    </select>
                    @error('recipients')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    @error('recipients.*')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="d-block">Recipient Group</label>
                    <select id="recipientGroups" name="recipient_groups[]" class="form-control @error('recipient_groups') is-invalid @enderror" data-style="btn btn-outline-light btn-md btn-block tx-left" multiple>
                        
                        @php($groupIds = $campaign_recipients->pluck('group_id')->toArray())

                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" @if (in_array($group->id, old('recipient_groups', []))  || in_array($group->id, $groupIds)) selected @endif>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('recipient_groups')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    @error('recipient_groups.*')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="d-block">Content *</label>
                    @if ($campaign->id == 1)
                        <label class="d-block"> Note: {Article Title} will change to the selected article title. {Article Link Button} will change to HTML button with selected article url. </label>
                    @endif
                    <textarea name="content" id="cke_editor1" rows="10" cols="80" required>{{ old('content', $campaign->content) }}</textarea>
                    @error('content')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <span class="invalid-feedback" role="alert" id="long_descriptionRequired" style="display: none;">
                        <strong>The content field is required</strong>
                    </span>
                </div>
            </div>
            <div class="col-lg-12 mg-t-10 pd-b-40">
                <input class="btn btn-primary btn-sm tx-uppercase tx-semibold" name="submit" type="submit" value="save only">
                <input class="btn btn-primary btn-sm tx-uppercase tx-semibold" name="submit" type="submit" value="save and send">
                <a  href="{{ route('mailing-list.campaigns.index') }}" class="btn btn-outline-secondary btn-sm tx-uppercase tx-semibold">Cancel</a>
            </div>
        </form>

    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $('#recipients').select2({ closeOnSelect: false });
        $('#recipientGroups').select2({ closeOnSelect: false });
        $('#recipients').trigger('change');
        $('#recipientGroups').trigger('change');
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.

        var CSRFToken = $('meta[name="csrf-token"]').attr('content');
        var CKEditorOptions = {
          filebrowserImageBrowseUrl: app__url_prefix+'/laravel-filemanager?type=Images',
          filebrowserImageUploadUrl: app__url_prefix+'/laravel-filemanager/upload?type=Images&_token='+CSRFToken,
          filebrowserBrowseUrl: app__url_prefix+'/laravel-filemanager?type=Files',
          filebrowserUploadUrl: app__url_prefix+'/laravel-filemanager/upload?type=Files&_token='+CSRFToken,
          allowedContent: true,
        };
        
        let editor = CKEDITOR.replace('content', CKEditorOptions);
        editor.on('required', function (evt) {
            if ($('.invalid-feedback').length == 1) {
                $('#long_descriptionRequired').show();
            }
            $('#cke_editor1').addClass('is-invalid');
            evt.cancel();
        });
    </script>
@endsection
