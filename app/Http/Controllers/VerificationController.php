<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function showReVerificationForm()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request)
    {
        $request->fulfill();
        return redirect('/');
    }

    public function sendVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }
}
