<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentLoginDetailsController extends Controller
{
    public function index(Request $request)
{
    $query = Student::query();

    // Search by name, class, or email
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('surname', 'LIKE', "%{$search}%")
              ->orWhere('firstname', 'LIKE', "%{$search}%")
              ->orWhere('class', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
    }

    $students = $query->paginate(10); // Paginate results

    return view('admin.login-details.student', compact('students'));
}
}

