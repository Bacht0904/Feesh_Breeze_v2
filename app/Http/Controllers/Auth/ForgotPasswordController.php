<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest'); // Ensure this controller is only accessible to guests              
    }
    /**
     * Show the form for requesting a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');        
    }
    /**
     * Handle a request to send a password reset link to the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        
        // Send the password reset link
        $response = $this->broker()->sendResetLink($request->only('email'));

        // Check if the response was successful
        if ($response === \Password::RESET_LINK_SENT) {
            return back()->with('status', 'Password reset link sent to your email address.');
        } else {
            return back()->withErrors(['email' => 'Failed to send password reset link. Please try again.']);
        }       
    }
}
