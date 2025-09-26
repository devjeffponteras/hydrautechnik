<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;

use Facades\App\Helpers\ListingHelper;
use App\Helpers\Setting;

use Illuminate\Support\Facades\Input;
use App\Http\Requests\UserRequest;

use App\Mail\{UpdatePasswordMail,AddNewUserMail};

use App\Models\{Permission, Role, ActivityLog, FileDownloadCategory, User, Page};

use Auth;


class UserController extends Controller
{
    use SendsPasswordResetEmails;

    private $searchFields = ['name'];

    public function __construct()
    {
        Permission::module_init($this, 'user');
    }

    public function index()
    {
        $listing = ListingHelper::required_condition('role_id', '<>', 6);
        $listing->required_condition('role_id', '<>', 2);
        $users = $listing->simple_search(User::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.users.index',compact('users','filter', 'searchType'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('id', [2, 6])->orderBy('name','asc')->get();
        return view('admin.users.create',compact('roles'));
    }

    public function store(UserRequest $request)
    {
    //    if(User::where('name',$request->fname.' '.$request->lname)->exists()){
    //        return back()->with('duplicate', __('standard.users.duplicate_email'));
    //    } else {
        $user = User::create([
            'firstname'      => $request->fname,
            'lastname'       => $request->lname,
            'name'           => $request->fname.' '.$request->lname,
            'password'       => str_random(32),
            'email'          => $request->email,
            'role_id'        => $request->role,
            'is_active'      => 1,
            'user_id'        => Auth::id(),
            'remember_token' => str_random(10)
        ]);

        $user->send_reset_temporary_password_email();

        return redirect()->route('users.index')->with('success', 'Pending for activation. Please remind the user to check the email and activate the account.');
    //    }
    }

    public function edit($id)
    {
        $roles = Role::whereNotIn('id', [2, 6])->orderBy('name','asc')->get();
        $user = User::where('id',$id)->first();

        return view('admin.users.edit',compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        Validator::make($request->all(), [
            'fname' => 'required|max:150',
            'lname' => 'required|max:150',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'role' => 'required|exists:role,id'
        ])->validate();

        $user->update([
            'firstname'=> $request->fname,
            'lastname' => $request->lname,
            'name'     => $request->fname.' '.$request->lname,
            'email'    => $request->email,
            'role_id'  => $request->role,
            'user_id'  => Auth::id(),
        ]);

        return redirect()->route('users.edit', $user->id)->with('success', __('standard.users.update_success'));
    }

    public function deactivate(Request $request)
    {
        $user = User::find($request->user_id);

        $user->update([
            'is_active' => 0,
            'user_id'   => Auth::id(),
        ]);
        $user->delete();

        return back()->with('success', __('standard.users.status_success', ['status' => 'deactivated']));
    }

    public function activate(Request $request)
    {
        $user = User::withTrashed()->find($request->user_id);

        $user->update([
            'is_active' => 1,
            'user_id'   => Auth::id(),
        ]);
        $user->restore();

        return back()->with('success', __('standard.users.status_success', ['status' => 'activated']));
    }


    public function show($id, $filter = null)
    {
        $searchFields = ['db_table'];
        $filterFields = ['activity_date', 'db_table'];

        $user = User::withTrashed()->find($id);


        $listing = ListingHelper::required_condition('log_by', '=', $id)->sort_by('activity_date')->filter_fields($filterFields);
        $logs = $listing->simple_search(ActivityLog::class, $searchFields);

        // Simple search init data
        $filter = $listing->get_filter($searchFields);
        $searchType = 'simple_search';

        return view('admin.users.profile',compact('user','logs', 'filter', 'searchType'));
    }

    public function filter(Request $request)
    {
        $params = $request->all();

        return $this->apply_filter($params);
    }

    public function apply_filter($param = null)
    {
        $user = User::where('id',$param['id'])->first();

        if(isset($param['order'])){
            $logs = ActivityLog::where('log_by',$param['id'])->orderBy($param['sort'],$param['order'])->paginate($param['pageLimit']);
        } else {
            $logs = ActivityLog::where('log_by',$param['id'])->paginate($param['pageLimit']);
        }

        return view('admin.users.profile',compact('user','logs','param'));
    }




    public function members()
    {
        $listing = ListingHelper::required_condition('role_id', '=', 2);
        $users = $listing->simple_search(User::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.ecommerce.members.index',compact('users','filter', 'searchType'));
    }

    public function member_create()
    {
        $departments = FileDownloadCategory::where('type', 0)->orderBy('title', 'asc')->get();

        return view('admin.ecommerce.members.create', compact('departments'));
    }

    public function member_edit($id)
    {
        $departments = FileDownloadCategory::where('type', 0)->orderBy('title', 'asc')->get();
        $user = User::find($id);

        return view('admin.ecommerce.members.edit', compact('departments','user'));
    }

    public function member_update(Request $request)
    {
        Validator::make($request->all(), [
            'fname' => 'required|max:150',
            'lname' => 'required|max:150',
            'email' => 'required|email|max:150',
        ])->validate();

        $requestData = $request->all();

        $requestData['firstname'] = $request->fname;
        $requestData['lastname'] = $request->lname;
        $requestData['name'] = $request->fname.' '.$request->lname;
        $requestData['user_id'] = Auth::id();

        $arr_departments = [];
        foreach($requestData['department_id'] as $dept){
            array_push($arr_departments, $dept);
        }

        $requestData['department_id'] = json_encode($arr_departments);

        $user = User::find($request->id)->update($requestData);

        return redirect()->route('members.index')->with('success', 'Member info has been updated.');
    }

    public function member_store(UserRequest $request)
    {
        $requestData = $request->all();

        $requestData['firstname'] = $request->fname;
        $requestData['lastname'] = $request->lname;
        $requestData['name'] = $request->fname.' '.$request->lname;
        $requestData['password'] = str_random(32);
        $requestData['role_id'] = $request->role;
        $requestData['is_active'] = 1;
        $requestData['user_id'] = Auth::id();
        $requestData['remember_token'] = str_random(10);

        $arr_departments = [];
        foreach($requestData['department_id'] as $dept){
            array_push($arr_departments, $dept);
        }

        $requestData['department_id'] = json_encode($arr_departments);

        $user = User::create($requestData);
        $user->send_reset_temporary_password_email();

        return redirect()->route('members.index')->with('success', 'Pending for activation. Please remind the member to check the email and activate the account.');
    }

}
