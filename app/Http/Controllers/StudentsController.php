<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $students = Student::select('student_id', 'name', 'surname')->when($search, function ($query, $search) {
                return $query->where('student_id', 'like', $search . '%');
            })->paginate(100);
        } else {
            $students = Student::select('student_id', 'name', 'surname')->paginate(100);
        }

        return view('admin.students', compact('students'));
    }

    public function store(Request $request)
    {
        try {
            $name = $request->input('name');
            $surname = $request->input('surname');
            $sid = $request->input('sid');
        
            // Create and save the new book
            $student = new Student;
            $student->name = $name;
            $student->surname = $surname;
            $student->student_id = $sid;
            
            $student->save();
            
            return response()->json([
                'message' => 'Student added successfully!',
                'book' => $student
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function getdata(Request $request)
    {
        try {
            $id = $request->query('sid');
            $student = Student::where('student_id', $id)->firstOrFail();
            return response()->json($student);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function edit(Request $request)
    {
        try {
            $student = Student::where('student_id', $request->input('sid'))->firstOrFail();
        
            $student->name = $request->input('name');
            $student->surname = $request->input('surname');
            
            $student->save();
            
            return response()->json([
                'message' => 'Student edit successfully!',
                'student' => $student
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $id = $request->query('sid');
            $student = Student::where('student_id', $id)->firstOrFail();
            $student->delete();
    
            return response()->json(['message' => 'Student deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete the student.'], 500);
        }
    }

    public function upload(Request $request)
    {
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('student_images', $imageName, 'public');


                $student = Student::where('student_id', $request->input('sid'))->firstOrFail();
                $student->image = $imageName;
                $student->save();

                return response()->json([
                    'message' => 'Image uploaded successfully!',
                    'image_name' => $imageName,
                ]);
            } else {
                return response()->json([
                    'message' => 'No image uploaded!',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
