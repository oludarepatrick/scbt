<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
    protected function create(array $data)
{
    // Generate a unique 6-digit student ID
    $studentId = mt_rand(100000, 999999);

    // First, create the user in the users table
    $user = User::create([
        'name' => strtoupper(($data['firstname'] ?? '') . ' ' . ($data['lastname'] ?? '')), // Combine names properly
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'phone' => $data['phone'] ?? null,
        'occupation' => $data['category'], // Save category
        'is_admin' => $data['category'] === 'Staff' ? 1 : 2, // Assign 1 for Staff, 2 for Student
        'visible_password' => $data['password'], // Store plain password (not recommended)
        'stud_id' => $studentId, // Store generated student ID
    ]);
    // If the user is a Student, save additional details in the students table
    if ($data['category'] === 'Student') {
        Student::create([
            'student_id' => $studentId, // Store same 6-digit student ID
            'surname' => strtoupper($data['lastname'] ?? ''),
            'firstname' => strtoupper($data['firstname'] ?? ''),
            'phone' => $data['phone'] ?? null,
            'dob' => $data['dob'] ?? null,
            'sex' => $data['sex'] ?? null, // Ensure this matches the validation field
            'class' => $data['class'] ?? null,
            'class_division' => $data['class_division'] ?? null,
            'password' => Hash::make($data['password']),
            'username' => $data['email'],
            'status' => 'ACTIVE', // Default to ACTIVE
            'session' => $data['session'] ?? null,
            'payment_status' => 'PAID'
        ]);
    }

    return $user; // Ensure Laravel continues with the default flow
}
}
