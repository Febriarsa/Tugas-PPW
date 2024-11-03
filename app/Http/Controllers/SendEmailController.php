<?php

 namespace App\Http\Controllers;

 use Illuminate\Http\Request;
 use Mail;
 use App\Mail\SendEmail;
 use App\Jobs\SendMailJob;

 class SendEmailController extends Controller
 {
    public function index()
    {
        return view('emails.kirim-email');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250',
            'number' => 'required|string|max:15',
            'address' => 'required|string|max:500',
        ]);

        $data = $request->all();
        dispatch(new SendMailJob($data));

        return redirect()->route('kirim-email')
            ->with('success', 'Email berhasil dikirim');
    }

 }