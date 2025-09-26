<?php

namespace App\Http\Controllers\Cms4Controllers;

use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;
use App\Http\Controllers\Controller;

use App\Models\Permission;
use App\Models\MobileAlbum;
use App\Models\MobileBanner;
use App\Models\Option;

use Storage;

class MobileAlbumController extends Controller
{
    private $searchFields = ['name'];

    public function __construct()
    {
        Permission::module_init($this, 'mobile_banner');
    }

    public function index()
    {
        $animations = Option::where('type', 'animation')->get();

        $listing = ListingHelper::required_condition('type', '!=', 'main_banner');
        $mobile_albums = $listing->simple_search(MobileAlbum::class, $this->searchFields);
        
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        $this->delete_temporary_banner_folder();

        return view('admin.cms4.mobile-banners.index', compact('mobile_albums', 'animations', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $animations = Option::where('type', 'animation')->get();

        return view('admin.cms4.mobile-banners.create', compact('animations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (MobileAlbum::has_invalid_data() || MobileBanner::has_invalid_data()) {
            $errors = MobileAlbum::get_error_messages()
                ->merge(MobileBanner::get_error_messages());

            return back()->withErrors($errors)->withInput();
        }

        $requestData = request()->all();

        $requestData['user_id'] = auth()->id();
        $requestData['status'] = $request->has('status') ? 1 : 0;

        $mobile_album = MobileAlbum::create($requestData);

        $banners = $this->set_order(request('banners'));

        $banners = $this->move_banner_to_official_folder($banners);

        $this->delete_temporary_banner_folder();

        $mobile_album->addBanners($banners);

        return redirect()->route('mobile-albums.index')->with('success', __('standard.banner.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MobileAlbum  $mobile_album
     * @return \Illuminate\Http\Response
     */
    public function show(MobileAlbum $mobile_album)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MobileAlbum  $mobile_album
     * @return \Illuminate\Http\Response
     */
    public function edit(MobileAlbum $mobile_album)
    {
        $animations = Option::where('type', 'animation')->get();

        if ($mobile_album->type == 'main_banner') {
            return view('admin.cms4.mobile-banners.home', compact('mobile_album', 'animations'));
        }

        return view('admin.cms4.mobile-banners.edit', compact('mobile_album', 'animations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MobileAlbum  $mobile_album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MobileAlbum $mobile_album)
    {

        if (MobileAlbum::has_invalid_data() || MobileBanner::has_invalid_data()) {
            $errors = MobileAlbum::get_error_messages()
                ->merge(MobileBanner::get_error_messages());

            return back()->withErrors($errors)->withInput();
        }

        $banners = $this->set_order(request('banners'));

        $updateData = request()->all();
        $updateData['banner_type'] = $request->has('banner_type') ? 'video' : 'image';
        $updateData['status'] = $request->has('status') ? 1 : 0;

        $newBanners = $this->get_new_banners($banners);
        $removeBanners = [];

        if ($mobile_album->banner_type != $updateData['banner_type'] || ($updateData['banner_type'] == 'video' && count($newBanners))) {
            if ($mobile_album->banners()->count()) {
                $removeBanners = $mobile_album->banners()->pluck('id')->toArray();
            }
        } else {
            $removeBanners = request('remove_banners');
        }

        $mobile_album->update($updateData);

        $this->update_banners($this->get_album_banners($banners));

        $this->remove_banners_from_album($removeBanners);

        $newBanners = $this->move_banner_to_official_folder($newBanners);

        $mobile_album->addBanners($newBanners);

        return back()->with('success', __('standard.banner.update_success'));
    }

    public function change_status($id)
    {

        $album = MobileAlbum::where('id', $id)->first();

        if($album->status == 1){
            MobileAlbum::whereId((int) $id)
            ->update([
                'status'  => 0
            ]);
        }
        else{
            MobileAlbum::whereId((int) $id)
            ->update([
                'status'  => 1
            ]);
        }


        return back()->with('success', "Successfully updated an album");
    }

    public function quick_update(Request $request, MobileAlbum $mobile_album)
    {
        if (MobileAlbum::has_invalid_quick_edit_data()) {
            return back()->withErrors(MobileAlbum::get_quick_edit_error_messages())->withInput();
        }

        $mobile_album->update(request()->all());

        if($mobile_album){
            return redirect()->route('mobile-albums.index')->with('success', __('standard.banner.update_success'));
        }

        return redirect()->route('mobile-albums.index');
    }

    public function update_banners($banners)
    {
        foreach ($banners as $banner) {
            if ($banner) {
                $bnr = MobileBanner::find($banner['id']);

                $bnr->update($banner);
                MobileAlbum::find($bnr->album_id)->update([
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function remove_banners_from_album($banners)
    {
        MobileBanner::find($banners ?? [])->each(function ($banner, $key) {
            $imagePath = $this->get_banner_path_in_storage($banner->image_path);
            Storage::disk('public')->delete($imagePath);
            $banner->update(['user_id' => auth()->id()]);
            $banner->delete();

            MobileAlbum::find($banner->album_id)->update([
                'updated_at' => now()
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\MobileAlbum $mobile_album
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(MobileAlbum $mobile_album)
    {
        $mobile_album->update(['user_id' => auth()->id()]);
        if ($mobile_album->delete()) {
            return back()->with('success', __('standard.banner.delete_success'));
        } else {
            return back()->with('error', __('standard.banner.delete_failed'));
        }
    }

    public function destroy_many()
    {
        $mobile_albumIds = explode(',', request('ids'));
        if (sizeof($mobile_albumIds) > 0 ) {
            $delete = MobileAlbum::whereIn('id', $mobile_albumIds)->delete();
            if ($delete) {
                return back()->with('success', __('standard.banner.delete_success'));
            }
        }

        return back()->with('error', 'Failed to delete an album.');
    }

    public function restore($mobile_album)
    {
        MobileAlbum::withTrashed()->findOrFail($mobile_album)->restore();

        return back()->with('success', __('standard.banner.restore_success'));
    }

    public function get_album_details(MobileAlbum $mobile_album) {

        $banner_paths = $mobile_album->banners->map(function ($item, $key) {
            return $item->image_path;
        })->toArray();

        $returnData = [
            'banner_paths' => $banner_paths,
            'transition_in' => $mobile_album->animationIn->value,
            'transition_out' => $mobile_album->animationOut->value,
            'transition' => $mobile_album->transition,
        ];

        return response()->json($returnData);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('banner')) {

            $newFile = $this->upload_file_to_temporary_storage($request->file('banner'));

            return response()->json([
                'status' => 'success',
                'image_url' => $newFile['url'],
                'image_name' => $newFile['name'],
                'image_path' => $newFile['path'],
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'image_url' => '',
            'image_name' => ''
        ]);
    }

    public function make_unique_file_name($folder, $fileName)
    {
        $fileNames = explode(".", $fileName);
        $count = 2;
        $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
        while(Storage::disk('public')->exists($folder.'/'.$newFilename)) {
            $count += 1;
            $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
        }

        return $newFilename;
    }

    public function upload_file_to_temporary_storage($file)
    {
        $temporaryFolder = 'temporary_banners'.auth()->id();
        $fileName = $file->getClientOriginalName();
        if (Storage::disk('public')->exists($temporaryFolder.'/'.$fileName)) {
            $fileName = $this->make_unique_file_name($temporaryFolder, $fileName);
        }

        $path = Storage::disk('public')->putFileAs($temporaryFolder, $file, $fileName);
        $url = Storage::disk('public')->url($path);

        return [
            'path' => $temporaryFolder.'/'.$fileName,
            'name' => $fileName,
            'url' => $url
        ];
    }


    public function get_album_banners($banners)
    {
        return array_filter($banners, function ($banner) {
            return isset($banner['id']);
        });
    }

    public function get_new_banners($banners)
    {
        return array_filter($banners, function ($banner) {
            return !isset($banner['id']);
        });
    }

    public function set_order($banners = [])
    {
        $banners = $banners ?? [];

        $count = 1;
        foreach($banners as $key => $banner) {
            $banners[$key]['order'] = $count;
            $count += 1;
        }

        return $banners;
    }

    public function move_banner_to_official_folder($banners)
    {
        foreach ($banners as $key => $banner) {
            $temporaryPath = $this->get_banner_path_in_storage($banners[$key]['image_path']);
            $fileName = $this->get_banner_file_name($banners[$key]['image_path']);
            $bannerFolder = '';

            $banners[$key]['image_path'] = $this->move_to_banners_folder($temporaryPath, $bannerFolder.$fileName);
        }

        return $banners;
    }

    public function move_to_banners_folder($temporaryPath, $fileName)
    {
        $folder = 'banners/';
        if (Storage::disk('public')->exists($folder.$fileName)) {
            $fileName = $this->make_unique_file_name($folder, $fileName);
        }

        $newPath = $folder.$fileName;
        Storage::disk('public')->move($temporaryPath, $newPath);
        return Storage::disk('public')->url($newPath);
    }

    public function get_banner_path_in_storage($path)
    {
        $paths = explode('storage/', $path);

        if (count($paths) == 1) {
            return '';
        }

        return explode('storage/', $path)[1];
    }

    public function get_banner_file_name($path)
    {
        $temporaryFolder = 'temporary_banners'.auth()->id();
        return explode($temporaryFolder, $path)[1];
    }

    public function delete_temporary_banner_folder()
    {
        $temporaryFolder = 'temporary_banners'.auth()->id();
        $files = Storage::disk('public')->allFiles($temporaryFolder);
        $directories = Storage::disk('public')->allDirectories($temporaryFolder);
        Storage::disk('public')->delete($files);
        Storage::disk('public')->delete($directories);
    }
}
