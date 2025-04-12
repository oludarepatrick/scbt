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
        $query->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
    }

    $staff = $query->paginate(10); // Paginate results

    return view('admin.login-details.staff', compact('staff'));
}
}

