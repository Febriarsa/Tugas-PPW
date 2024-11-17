<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LoginRegisterController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->only(['register', 'login', 'authenticate', 'store']);
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'photo' => 'nullable|image|max:2048'
        ]);

        $photoPath = null;

        // if ($request->hasFile('photo')) {
        //     $filenameWithExt = $request->file('photo')->getClientOriginalName();
        //     $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //     $extension = $request->file('photo')->getClientOriginalExtension();
        //     $photoPath = $filename . '_' . time() . '.' . $extension;
        //     $path = $request->file('photo')->storeAs('photos', $photoPath);
            
        //     if (!$path) {
        //         return back()->withErrors(['photo' => 'Photo upload failed, please try again.']);
        //     }
        // }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photoPath,
        ]);

        Auth::attempt($request->only('email', 'password'));
        $request->session()->regenerate();
        return redirect()->route('buku')
        ->withSuccess('You have successfully registered & logged in!');
    }
    
    
    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.login');
    }
    
    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if(Auth::user()->level=="admin"){
                
                return redirect()->route('dashboard')
                ->withSuccess('You have successfully logged in!');
            }
            return redirect()->route('buku.index')
            ->withSuccess('You have successfully logged in!');
        }
        
        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
            ])->onlyInput('email');
        }
        
        /**
         * Display a dashboard to authenticated users.
         *
         * @return \Illuminate\Http\Response
         */
        public function dashboard()
        {
            if (Auth::check()) {
            return view('auth.dashboard');
        }
        
        return redirect()->route('login')
        ->withErrors([
            'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
        }
        
        /**
         * Log out the user from application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function logout(Request $request)
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
            ->withSuccess('You have logged out succesfully');
    }
}
