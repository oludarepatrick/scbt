<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\SchoolInfo;

class SchoolSetupController extends Controller
{
    // CLASS FUNCTIONS
    public function showClasses()
    {
        $classes = ClassModel::all();
        return view('backend.subject.classes', compact('classes'));
    }

    public function storeClass(Request $request)
    {
        $request->validate(['name' => 'required|unique:classes,name']);
        ClassModel::create(['name' => $request->name]);
        return back()->with('success', 'Class added successfully!');
    }

    public function updateClass(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);
        $request->validate(['name' => 'required|unique:classes,name,' . $id]);
        $class->update(['name' => $request->name]);
        return back()->with('success', 'Class updated successfully!');
    }

    public function destroyClass($id)
    {
        ClassModel::findOrFail($id)->delete();
        return back()->with('success', 'Class deleted successfully!');
    }

    // SUBJECT FUNCTIONS
    public function showSubjects()
    {
        $subjects = Subject::all();
        return view('backend.subject.subjects', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate(['name' => 'required|unique:subjects,name']);
        Subject::create(['name' => $request->name]);
        return back()->with('success', 'Subject added successfully!');
    }

    public function updateSubject(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $request->validate(['name' => 'required|unique:subjects,name,' . $id]);
        $subject->update(['name' => $request->name]);
        return back()->with('success', 'Subject updated successfully!');
    }

    public function destroySubject($id)
    {
        Subject::findOrFail($id)->delete();
        return back()->with('success', 'Subject deleted successfully!');
    }

    // SCHOOL INFO FUNCTIONS
    public function showInfo()
    {
        $info = SchoolInfo::all();
        return view('backend.schoolinfo.info', compact('info'));
    }

    public function storeInfo(Request $request)
    {
        $request->validate(['name' => 'required|unique:school_infos,name']);
        SchoolInfo::create([
         'name' => $request->name,
         'email' => $request->email, 
         'phone' => $request->phone,
         'session' => $request->session,
         'term' => $request->term,
         'status' => '1'
        ]);
        return back()->with('success', 'Info added successfully!');
    }

    public function updateInfo(Request $request, $id)
    {
        $info = SchoolInfo::findOrFail($id);
        $request->validate(['name' => 'required|unique:classes,name,' . $id]);
        $info->update(['name' => $request->name]);
        return back()->with('success', 'Info updated successfully!');
    }

    public function destroyInfo($id)
    {
        SchoolInfo::findOrFail($id)->delete();
        return back()->with('success', 'Info deleted successfully!');
    }
}
