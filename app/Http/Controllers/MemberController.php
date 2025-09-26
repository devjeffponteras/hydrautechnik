<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Page, User, FileDownload, FileDownloadCategory};

use Illuminate\Support\Facades\Response;

use Auth;

class MemberController extends Controller
{
    public function multiple_change_status(Request $request)
    {
        $categories = explode("|", $request->categories);

        foreach ($categories as $category) {
            $publish = User::where('is_active', '!=', $request->status)->whereId((int) $category)->update([
                'is_active'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.members.category_update_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $users = explode("|",$request->categories);

        foreach($users as $user){
            User::whereId((int) $user)->update(['user_id' => Auth::id() ]);
            User::whereId((int) $user)->delete();
        }

        return back()->with('success', __('standard.members.multiple_delete_success'));
    }

    public function restore($category){
        User::withTrashed()->find($category)->update(['user_id' => Auth::id() ]);
        User::whereId((int) $category)->restore();

        return back()->with('success', __('standard.members.restore_category_success'));
    }

    public function update_status($id,$status)
    {
        User::where('id',$id)->update([
            'is_active' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.members.category_update_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $category = User::findOrFail($request->categories);
        $category->update([
            'user_id' => Auth::id(),
            'is_active' => 0
        ]);
        
        $category->delete();

        return back()->with('success', __('standard.members.single_delete_success'));

    }





    public function file_download(Request $request)
    {
        $page = new Page();
        $page->name = 'File Downloads';


        $arr_user_department = substr(auth()->user()->department_id, 1, -1);
        $departments = explode(',', $arr_user_department);

        $arr_departments = [];
        foreach($departments as $dept){
            array_push($arr_departments, $dept);
        }

        $files = FileDownload::whereNotNull('id')->get();
        $arr_files = [];
        $arr_file_departments = [];
        foreach($files as $file){
            $fileDepartments  = substr($file->department_id, 1, -1);
            $file_departments = explode(',', $fileDepartments);



            foreach($file_departments as $fdept){
                // array_push($arr_file_departments, $fdept);
                if(in_array($fdept, $arr_departments)){
                    array_push($arr_files, $file->id);
                }
            }

            // foreach($departments as $dept){
            //     if(in_array($dept, $arr_file_departments)){
            //         if(!in_array($file->id, $arr_files)){
            //             array_push($arr_files, $file->id);
            //         }
            //     }
            // }
        }

        $categories = FileDownloadCategory::where('type', 1)->orderBy('title', 'asc')->get();

        $member_files = FileDownload::whereIn('id', $arr_files);

        if(isset($request->category)){
            if(isset($request->searchtxt) && $request->searchtxt <> ''){
                $member_files->where('title', 'LIKE', '%'.$request->searchtxt.'%');
            }

            if($request->category != 'all'){
                $member_files->where('category_id', $request->category);
            }
            
        } else {
            $member_files->whereIn('id', $arr_files)->orderBy('title','asc')->paginate(20);
        }


        $member_files = $member_files->orderBy('title','asc')->paginate(20);

        

        return view('theme.pages.member.file-download', compact('page','member_files', 'categories'));
    }

    public function member_login(Request $request) {

        $page = new Page();
        $page->name = 'Member Login';

        return view('theme.pages.member.login',compact('page'));
    }

    public function member_post_login(Request $request)
    {
        $userCredentials = [
            'email'    => $request->email,
            'password' => $request->password
        ];

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            unset($userCredentials['username']);
            $userCredentials['email'] = $request->email;
        }
        
        if (Auth::attempt($userCredentials)) {
       
            if(Auth::user()->role_id <> 2){ // block cms users from using this login form
                Auth::logout();
                return back()->with('error', 'Administrative accounts are not allowed to login as customer.'); 
            }

            if(Auth::user()->is_active <> 1){ // block inactive users from using this login form
                Auth::logout();
                return back()->with('error', 'Account is not active.'); 
            }

            return redirect(route('member.manage-account'));
        } else {
            Auth::logout();
            return back()->with('error', __('auth.login.incorrect_input'));    
        }
    }

    public function forgot_password(Request $request) {

        $page = new Page();
        $page->name = 'Member Forgot Password';

        return view('theme.pages.member.forgot-password', compact('page'));

    }

    public function showResetForm(Request $request, $token = null)
    {
        $credentials =  $request->only('email');

        $page = new Page();
        $page->name = 'Reset Password';
 
        if (!$token){
            return redirect()->route('customer-front.forgot_password')->with('error','Your link is expired. Please reset your password again.');
        } 

        return view('theme.pages.member.reset-password',)->with(
            ['token' => $token, 'email' => $request->email, 'page' => $page]
        );
    }

    public function reset(Request $request)
    {
        $credentials = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            "password" => [
                'required',
                'confirmed',
                'min:8',
                'max:150',
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'password_confirmation' => 'required|same:password',
        ]);

        User::where('email', $request->email)->update(['password' => bcrypt($request->password)]);

        return redirect()->route('customer-front.login')->with('success', 'Password has been reset.');
    }


    public function manage_account(Request $request)
    {
        $page = new Page;
        $page->name = 'My Account';

        $member = auth()->user();
        $user = auth()->user();

        return view('theme.pages.member.manage-account', compact('member', 'user', 'page'));
    }

    public function change_password()
    {
        $page = new Page();
        $page->name = 'Change Password';

        return view('theme.pages.member.change-password',compact('page'));
    }

    public function logout()
    {
        Auth::logout();

        return redirect(route('member.login'));
    }



}
