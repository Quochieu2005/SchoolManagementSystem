<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        // echo bcrypt(12345678);
        // die;    
        return view('auth.login');
    }

    public function post_login(Request $request)
    {
        // dd($request->all());
        if(Auth::attempt(['email' => $request->email , 'password' => $request->password , 'trang_thai' => 1] , true))
        {
            if(Auth::user()->is_admin === 5)
                {
                    return redirect('teacher/dashboard');
                }
            elseif(Auth::user()->is_admin === 6)
                {
                    return redirect('student/dashboard');
                }
            elseif(Auth::user()->is_admin === 7)
                {
                    return redirect('parent/dashboard');
                }
            else
                {
                    return redirect('cpanel/dashboard');
                }
        }
        else
        {
            return redirect()->back()->with('error' , 'Vui lòng nhập email và mật khẩu chính xác!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
