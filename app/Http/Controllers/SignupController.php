<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
//use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Student;
//use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



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
        //echo "hello";
        return view('auth.signup_form'); // Ensure you have this Blade file
    }
    
    public function create(Request $request)
    {
        //dd($request);
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
        
     
         // If user is a Student, store details in the students table
         if ($validatedData['category'] === 'Student') {

            $add = Student::create([
                 'student_id' => $studentId,
                 'surname' => strtoupper($validatedData['lastname']),
                 'firstname' => strtoupper($validatedData['firstname']),
                 'othername' => 'Null',
                 'phone' => $validatedData['phone'] ?? null,
                 'dob' => $validatedData['dob'] ?? null,
                 'sex' => $validatedData['sex'] ?? null,
                 'class' => $validatedData['class'] ?? null,
                 'class_division' => $validatedData['class_division'] ?? null,
                 'password' => Hash::make($validatedData['password']),
                 'visible_password' => $validatedData['password'],
                 'username' => $validatedData['email'],
                 'status' => 'ACTIVE',
                 'session' => $request->input('session'),
                 'payment_status' => 'PAID'
             ]);
           // echo $studentId = $add->sn;
           $studentId = isset($add->sn)? $add->sn:$add->id;
         }
         $user = User::create([
            'name' => strtoupper($validatedData['firstname'] . ' ' . $validatedData['lastname']),
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'] ?? null,
            'occupation' => $validatedData['category'],
            'is_admin' => $validatedData['category'] === 'Staff' ? 1 : 0,
            'visible_password' => $validatedData['password'], // ⚠ Not recommended for security
            'stud_id' => $studentId,
        ]);

         // Send email with login details
        //  Mail::to($validatedData['email'])->send(new UserRegistrationMail($user, $validatedData['password']));
         //Mail::to($validatedData['email'])->queue(new UserRegistrationMail($user, $validatedData['password']));



         return redirect()->route('login')->with('success', 'Registration successful. You can now log in.');
     }
}
