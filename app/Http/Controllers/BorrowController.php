<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Student;
use Illuminate\Support\Carbon;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('usid');
        $search = $request->query('search');

        $books = [];
        if ($userId) {
            if ($search) {
                $books = Borrow::select('borrowings.borrowing_id', 'borrowings.barcode', 'books.title', 'books.author', 'borrowings.borrowed_at', 'borrowings.returned_at', 'borrowings.status')
                    ->join('books', 'borrowings.barcode', '=', 'books.barcode')
                    ->where('borrowings.user_id', $userId)
                    ->where('borrowings.status', 'Borrowed')
                    ->where(function ($query) use ($search) {
                        $query->where('books.title', 'like', '%' . $search . '%')
                              ->orWhere('books.author', 'like', '%' . $search . '%');
                    })
                    ->paginate(100);
            } else {
                $books = Borrow::select('borrowings.borrowing_id', 'borrowings.barcode', 'books.title', 'borrowings.borrowed_at', 'borrowings.returned_at', 'borrowings.status')
                    ->join('books', 'borrowings.barcode', '=', 'books.barcode')
                    ->where('borrowings.user_id', $userId)
                    ->where('borrowings.status', 'Borrowed')
                    ->paginate(100);
            }
        }
        if ($userId) {
            $student = Student::select('image')->where('student_id', $userId)->first();
            $image = $student->image;
            return view('admin.borrow', compact('books', 'image'));
        }
        return view('admin.borrow', compact('books'));
    }

    public function borrow(Request $request)
    {
        try {
            $barcode = $request->input('barcode');
            $date = Carbon::createFromFormat('m/d/Y', $request->input('date'));

            $sid = $request->input('sid');
            
            $borrow = new Borrow;
            $borrow->user_id = $sid;
            $borrow->barcode = $barcode;
            $borrow->borrowed_at = Carbon::today();
            $borrow->returned_at = $date->toDateString();;
            $borrow->save();

            return response()->json([
                'message' => 'borrow request successfully!',
                'borrow' => $borrow
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function return(Request $request)
    {
        try {
            $borrow = Borrow::where('borrowing_id', $request->query('id'))->firstOrFail();
            $borrow->status = "Available";
            $borrow->save();

            return response()->json([
                'message' => 'return request successfully!',
                'return' => $borrow
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
