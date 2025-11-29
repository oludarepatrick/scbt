<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;

class StaffSubjectController extends Controller
{
    public function create()
    {
        $classes = ['GRADE 1', 'GRADE 2', 'GRADE 3', 'GRADE 4', 'GRADE 5', 'GRADE 6', 'SPECIAL CLASS', 'ENTRANCE', 'GENERAL', 'NURSERY 1', 'NURSERY 2', 'RECEPTION 1', 'RECEPTION 2'. 'JSS 1', 'JSS 2', 'JSS 3', 'SSS 1', 'SSS 2', 'SSS 3'];
        $subjects = ['MATHEMATICS', 'ENGLISH', 'GEOMETRY', 'LITERATURE', 'NATIONAL VALUE', 'BASIC SCIENCE', 'LITERACY', 'NUMERACY', 'BASIC SCIENCE & TECH', 'COMPUTER STUDIES', 'FRENCH', 'PHONICS & DICTION', 'HANDWRITING', 'PREVOCATIONAL STUDIES', 'IGBO LANG', 'CRK', 'YORUBA', 'HISTORY', 'ECONOMICS', 'BIOLOGY', 'CHEMISTRY', 'PHYSICS', 'AGRICULTURAL SCIENCE', 'GEOGRAPHY', 'COMMERCE', 'ACCOUNTING', 'CIVIC EDUCATION', 'LITERATURE IN ENGLISH', 'GOVERNMENT', 'SOCIAL STUDIES', 'VISUAL ART', 'TECHNICAL DRAWING', 'PHYSICAL EDUCATION', 'HOME ECONOMICS'];

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
        'name' => $request->subject,
        'subject' => $request->subject,
        'class_arm' => 'A',
    ]);
           

        return redirect()->back()->with('success', 'Subject Created successfully!');
    }
}
