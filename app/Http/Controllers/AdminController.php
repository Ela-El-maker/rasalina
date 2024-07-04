<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class AdminController extends Controller
{
    //
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Admin Logged out Successfully.',
            'alert-type' => 'info'
        );

        return redirect('/login')->with($notification);

        
        
    } // End Method


    public function Profile(){
        $id = Auth::user()->id;

        $adminData = User::find($id);

        return view('admin.admin_profile_view', compact('adminData'));
    }

    public function EditProfile() {
        $id = Auth::user()->id;

        $editData = User::find($id);

        return view('admin.admin_profile_edit', compact('editData'));
    }


    public function StoreProfile(Request $request) {
        
        $id = Auth::user()->id;
        $data = User::find($id);

        $data -> name = $request -> name;
        $data -> email = $request -> email;
        $data -> username = $request -> username;

        if($request->file('profile_image'))
        {
            $file = $request -> file('profile_image');

            $filename = date('YmdHi').$file->getClientOriginalName();

            $file ->move(public_path('uploads/admin_images'), $filename);
            $data['profile_image'] = $filename;
        }
        
        $data -> save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.profile')->with($notification);

    }

    public function ChangePassword() {
        return view('admin.admin_change_password');
    }

    public function updatePassword(Request $request) {
        $validateData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',

        ]);

        $hashedPassword = Auth::user()->password;
        
        if(Hash::check($request->old_password,$hashedPassword)){
            $users = User::find(Auth::id());
            $users->password = bcrypt($request->new_password);
            $users->save();

            session() ->flash('message', 'Password Updated Successfully');
            return  redirect()->back();
        }
        else{

            session() ->flash('message', 'Old Password does not match');
            return  redirect()->back();
        }
    }
}
