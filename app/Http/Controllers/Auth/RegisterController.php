<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    //use RegistersUsers;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
   protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'dob' => 'nullable|date',
            'sex' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'class' => 'required|string|max:100',
            'class_division' => 'nullable|string|max:50',
            'category' => 'required|in:Student,Staff', // Ensure category is required and valid
        ], [
            'email.unique' => 'The email address is already registered. Please use a different email.',
        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

     public function showRegistrationForm()
    {
        return view('auth.register'); // Ensure you have this Blade file
    }
    
     protected function register(Request $request)
     {
         // Validate user input
         $validatedData = $request->validate([
             'firstname' => ['required', 'string', 'max:255'],
             'lastname' => ['required', 'string', 'max:255'],
             'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
             'password' => ['required', 'string', 'min:8', 'confirmed'],
             'dob' => 'nullable|date',
             'sex' => 'required|string',
             'phone' => 'nullable|string|max:20',
             'class' => 'required|string|max:100',
             'class_division' => 'nullable|string|max:50',
             'category' => 'required|in:Student,Staff',
         ]);
        
     
         // Generate a unique 6-digit student ID
         $studentId = mt_rand(100000, 999999);
     
         // Create user
         $user = User::create([
             'name' => strtoupper($validatedData['firstname'] . ' ' . $validatedData['lastname']),
             'email' => $validatedData['email'],
             'password' => Hash::make($validatedData['password']),
             'phone' => $validatedData['phone'] ?? null,
             'occupation' => $validatedData['category'],
             'is_admin' => $validatedData['category'] === 'Staff' ? 1 : 2,
             'visible_password' => $validatedData['password'], // ⚠ Not recommended for security
             'stud_id' => $studentId,
         ]);
     
         // If user is a Student, store details in the students table
         if ($validatedData['category'] === 'Student') {
             Student::create([
                 'student_id' => $studentId,
                 'surname' => strtoupper($validatedData['lastname']),
                 'firstname' => strtoupper($validatedData['firstname']),
                 'phone' => $validatedData['phone'] ?? null,
                 'dob' => $validatedData['dob'] ?? null,
                 'sex' => $validatedData['sex'] ?? null,
                 'class' => $validatedData['class'] ?? null,
                 'class_division' => $validatedData['class_division'] ?? null,
                 'password' => Hash::make($validatedData['password']),
                 'username' => $validatedData['email'],
                 'status' => 'ACTIVE',
                 'session' => $request->input('session'),
                 'payment_status' => 'PAID'
             ]);
         }
     
         return redirect()->route('login')->with('success', 'Registration successful. You can now log in.');
     }
}
