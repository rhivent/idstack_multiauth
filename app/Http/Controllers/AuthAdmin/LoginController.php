<?php

namespace App\Http\Controllers\AuthAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; 

class LoginController extends Controller
{
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */



    public function __construct()
    {
        /* tidak hanya guest lgi disini ditambahkan secara spesifik
        dimana akun admin tidak dapat mengakses halaman login lagi,
        ketika admin sudah melakukan login, tentunya method logout tetap bisa
        ketika admin sudah login
        */

        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('authAdmin.login');
    }

    /* kita juga buat method login untuk menerima request credential yg dikirimkan oleh user, yang akan divalidasi apakah credential yg diinputkan oleh pengguna terdapat di tabel admins berikut beberapa responnya
    
    */

    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credential = [
            'email' => $request->email,
            'password' => $request->password
        ];

        //Attempt to log the user in
        if (Auth::guard('admin')->attempt($credential,$request->member)) {
            //If login successful, then redirect to their intended location
            return redirect()->intended(route('admin.home'));
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful, then back to the login view with form data

        return redirect()->back()->withInput($request->only('email','remember'));
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
