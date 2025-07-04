<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;


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
        return view('auth.password.email');        
    }
    /**
     * Handle a request to send a password reset link to the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(Request $request)
    {
        // $this->validate($request, ['email' => 'required|email']);
        
        // // Send the password reset link
        // $response = $this->broker()->sendResetLink($request->only('email'));

        // // Check if the response was successful
        // if ($response === \Password::RESET_LINK_SENT) {
        //     return back()->with('status', 'Password reset link sent to your email address.');
        // } else {
        //     return back()->withErrors(['email' => 'Failed to send password reset link. Please try again.']);
        // }       
        $request->validate(
            [
              'email'=> 'required|email|exists:users,email',
            ],
            [
                'email.required'=>'Email là bắt buộc',
                'email.email'=>'Email không hợp lệ',
                'email.exists'=>'Email không tồn tại trong hệ thống',
            ]
            );
            $status= Password::sendResetLink($request->only('email'));
            if($status === Password::RESET_LINK_SENT)
            {
                toastr()->success('Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
                return back();
            }
            
            toastr()->error('Không thể gửi lại email đặt lại mật khẩu.');
            return back()->withErrors(['email'=>__($status)]);
    }
}
