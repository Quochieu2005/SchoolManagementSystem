<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Services\CloudinaryService;


class UserController extends Controller
{

    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }
    public function change_password()
    {
        $meta_title = "Change Password";

        return view('backend.change-password', compact('meta_title'));
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($request->new_password === $request->confirm_password) {

            $user = Auth::user();

            if (Hash::check($request->old_password, $user->password)) {

                $user->password = bcrypt($request->new_password);
                $user->save();

                return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi!');
            } else {

                return redirect()->back()->with('error', 'Mật khẩu cũ không đúng!');
            }
        } else {

            return redirect()->back()->with('error', 'New password và Confirm password không khớp!');
        }
    }

    public function my_account()
    {
        $user = Auth::user();
        $meta_title = "My Account";

        return view('backend.my-account', compact('meta_title', 'user'));
    }

    public function update_account(Request $request)
    {
        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if (Auth::user()->is_admin != 3) {
            $user->last_name = $request->last_name;
        }

        if ($request->hasFile('profile_pic')) {

            $publicId = "{$user->slug}-{$user->id}";

            $upload = $this->cloudinary->uploadImage(
                $request->file('profile_pic'),
                'user/List',
                $publicId
            );

            $user->profile_pic = $upload['url'];
        }

        $user->save();

        return redirect()->back()->with('success', 'My account đã được cập nhật!');
    }
}
