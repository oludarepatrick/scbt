<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
//use App\Providers\RouteServiceProvider;
use App\Models\User;
//use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Services\ZeptoMailService;



class SignupController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    public function showRegistrationForm()
    {
        //echo "hello";
        return view('auth.signup_form'); // Ensure you have this Blade file
    }
    
   public function create(Request $request, ZeptoMailService $zeptomail)
{
    // ✅ Validate user input
    $validatedData = $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname'  => 'required|string|max:255',
        'email'     => 'required|string|email|max:255|unique:users',
        'password'  => 'required|string|min:6',
        'phone'     => 'nullable|string|max:20',
        'class'     => 'nullable|string|max:100',
        'category'  => 'required|in:Student,Staff',
        'term'      => 'nullable|string|max:50',
        'session'   => 'nullable|string|max:50',
    ]);

    // ✅ Create user record
    $user = User::create([
        'firstname'         => ucfirst($validatedData['firstname']),
        'lastname'          => ucfirst($validatedData['lastname']),
        'class'             => $validatedData['class'] ?? null,
        'email'             => $validatedData['email'],
        'password'          => Hash::make($validatedData['password']),
        'visible_password'  => $validatedData['password'], // plain text for internal use
        'category'          => $validatedData['category'],
        'phone'             => $validatedData['phone'] ?? null,
        'term'              => $validatedData['term'] ?? null,
        'session'           => $validatedData['session'] ?? null,
        'status'            => 1,
        'is_admin'          => $validatedData['category'] === 'Staff' ? 1 : 2, // 1=Staff, 2=Student
    ]);

    // ✅ Prepare merge variables for ZeptoMail template
    $mergeData = [
        'firstname' => $user->firstname,
        'lastname'  => $user->lastname,
        'email'     => $user->email,
        'class'     => $user->class,
        'password'  => $user->visible_password,
        'category'  => $user->category,
        'login_url' => route('login'),
    ];

    // ✅ Send email via ZeptoMail
   /* try {
        $zeptomail->sendTemplateEmail(
            'user-login-details',      // your ZeptoMail template key
            $user->email,              // recipient email
            $mergeInfo                 // template variables
        );
    } catch (\Exception $e) {
        \Log::error('ZeptoMail send failed: ' . $e->getMessage());
    }*/
    
        try {
        // --- 1. Notify school admin ---
        Http::withoutVerifying()
            ->withHeaders([
                'authorization' => 'Zoho-enczapikey ' . env('ZEPTOMAIL_API_KEY'),
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ])->timeout(30)
            ->post(env('ZEPTOMAIL_URL') . '/v1.1/email/template', [
                'template_key' => 'user-login-details',
                'from' => [
                    'address' => 'development@leverpay.io',
                    'name'    => 'School Admin'
                ],
                'to' => [
                    ['email_address' => ['address' => 'tenak09@gmail.com']]
                ],
                'merge_info' => $mergeData
            ]);
    } catch (\Exception $e) {
        Log::error('Failed to send admin registration email: ' . $e->getMessage());
    }

    // ✅ Redirect after registration
    return redirect()
        ->route('login')
        ->with('success', 'Registration successful! Login details have been sent to your email.');

    }
//ends here
    
}
