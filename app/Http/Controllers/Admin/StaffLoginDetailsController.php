<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffLoginDetailsController extends Controller
{
    public function index(Request $request)
{
    $query = User::query();

    // Search by name or email
    if ($request->has('search')) {
    $search = $request->input('search');

    $query->where('category', 'Staff')
          ->where(function ($q) use ($search) {
              $q->where('firstname', 'LIKE', "%{$search}%")
                ->orWhere('lastname', 'LIKE', "%{$search}%")
                ->orWhere('class', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
          });
} else {
    $query->where('category', 'Student');

    }

    $staff = $query->paginate(10); // Paginate results

    return view('admin.login-details.staff', compact('staff'));
}
}

