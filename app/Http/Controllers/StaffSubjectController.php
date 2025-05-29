<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;

class StaffSubjectController extends Controller
{
    public function create()
    {
        $classes = ['BASIC 1', 'BASIC 2', 'BASIC 3', 'BASIC 4', 'BASIC 5', 'BASIC 6', 'SPECIAL CLASS', 'ENTRANCE', 'GENERAL', 'NURSERY 1', 'NURSERY 2', 'RECEPTION 1', 'RECEPTION 2'];
        $subjects = ['MATHEMATICS', 'ENGLISH', 'GEOMETRY', 'LITERATURE', 'NATIONAL VALUE', 'BASIC SCIENCE', 'LITERACY', 'NUMERACY', 'BASIC SCIENCE & TECH', 'COMPUTER STUDIES', 'FRENCH', 'PHONICS & DICTION', 'HANDWRITING', 'PREVOCATIONAL STUDIES', 'IGBO LANG', 'CRK', 'YORUBA', 'HISTORY'];

        return view('backend.subject.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class' => 'required|string',
            'subject' => 'required|string',
        ]);

        Subject::create([
        'staff_id' => rand(1220, 9999), // Or use a fixed ID like 1 if needed
        'class' => $request->class,
        'subject' => $request->subject,
        'class_arm' => 'A',
    ]);
           

        return redirect()->back()->with('success', 'Subject Created successfully!');
    }
}
