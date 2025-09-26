<?php

namespace App\Http\Controllers\MailingList;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Facades\App\Helpers\ListingHelper;

use App\Models\{Permission, Group, Subscriber};

class GroupController extends Controller
{
    protected $searchFields = ['name'];

    public function __construct()
    {
        Permission::module_init($this, 'subscriber_group');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = ListingHelper::simple_search(Group::class, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.mailing-list.groups.index', compact('groups','filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscribers = Subscriber::all();

        return view('admin.mailing-list.groups.create', compact('subscribers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newGroup = $this->validate_data($request);

        $group = Group::create($newGroup);

        if (isset($newGroup['subscribers'])) {
            $group->subscribers()->sync($newGroup['subscribers']);
        }

        if ($group) {
            return redirect()->route('mailing-list.groups.index')->with(['success' => 'The subscriber has been added.']);
        } else {
            return redirect()->route('mailing-list.groups.create')->with(['error' => 'Failed to add subscriber. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Group $group)
    {
        $subscribers = Subscriber::all();

        return view('admin.mailing-list.groups.edit', compact('group','subscribers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $updateGroup = $this->validate_data($request);

        $group->update($updateGroup);

        if (isset($updateGroup['subscribers'])) {
            $group->subscribers()->sync($updateGroup['subscribers']);
        }

        if ($group) {
            return redirect()->route('mailing-list.groups.index')->with(['success' => 'The subscriber has been added.']);
        } else {
            return redirect()->route('mailing-list.groups.create')->with(['error' => 'Failed to add subscriber. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Group $group)
    {
        if ($group->delete()) {
            return back()->with('success', 'The download manager category has been deleted');
        } else {
            return back()->with('error', 'Failed to delete a download manager category. Please try again.');
        }
    }

    public function destroy_many()
    {
        $deleteIds = explode(',', request('ids'));
        if (sizeof($deleteIds) > 0 ) {
            $delete = Group::whereIn('id', $deleteIds)->delete();
            if ($delete) {
                return back()->with('success', 'The download manager category\s has been deleted');
            }
        }

        return back()->with('error', 'Failed to delete download manager category\s.');
    }

    public function restore($id)
    {
        Group::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'The download manager category has been restored');
    }

    public function validate_data(Request $request)
    {
        return $request->validate([
            'name' => 'max:150|required',
            'description' => '',
            'subscribers' => 'array'
        ]);
    }
}
