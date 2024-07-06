<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminController extends Controller
{
    // Logout method
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
    }

    // Profile method
    public function Profile()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('You must be logged in to access this page.');
        }

        $id = Auth::user()->id;
        $adminData = User::find($id);

        if (!$adminData) {
            return redirect()->route('login')->withErrors('Admin data not found.');
        }

        return view('admin.admin_profile_view', compact('adminData'));
    }

    // Edit profile method
    public function EditProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('You must be logged in to access this page.');
        }

        $id = Auth::user()->id;
        $editData = User::find($id);

        if (!$editData) {
            return redirect()->route('login')->withErrors('Edit data not found.');
        }

        return view('admin.admin_profile_edit', compact('editData'));
    }

    // Store profile method
    public function StoreProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('You must be logged in to access this page.');
        }

        $id = Auth::user()->id;
        $data = User::find($id);

        if (!$data) {
            return redirect()->route('login')->withErrors('User data not found.');
        }

        $data->name = $request->name;
        $data->email = $request->email;
        $data->username = $request->username;

        if ($request->file('profile_image')) {
            $file = $request->file('profile_image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/admin_images'), $filename);
            $data['profile_image'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.profile')->with($notification);
    }

    // Change password method
    public function ChangePassword()
    {
        return view('admin.admin_change_password');
    }

    // Update password method
    public function updatePassword(Request $request)
    {
        $validateData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);

        $hashedPassword = Auth::user()->password;

        if (Hash::check($request->old_password, $hashedPassword)) {
            $user = User::find(Auth::id());
            $user->password = bcrypt($request->new_password);
            $user->save();

            session()->flash('message', 'Password Updated Successfully');
            return redirect()->back();
        } else {
            session()->flash('message', 'Old Password does not match');
            return redirect()->back();
        }
    }
}
