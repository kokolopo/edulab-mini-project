<?php

namespace App\Http\Controllers;

use App\DataTables\StudentsDataTable;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = Student::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusCheckbox = $row->status == 1 ? 'checked' : '';
                    return '<input type="checkbox" class="status-checkbox" ' . $statusCheckbox . ' data-id="' . $row->id . '" data-original-title="Edit" class="edit status-checkbox">';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editStudent">Edit</a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteStudent">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('page.home');
    }

    public function store(Request $request)
    {
        Student::updateOrCreate(
            [
                'id' => $request->student_id
            ],
            [
                'name' => $request->name,
                'class' => $request->class,
            ]
        );

        return response()->json(['success' => 'Student saved successfully.']);
    }

    public function update(Request $request)
    {

        Student::updateOrCreate(
            [
                'id' => $request->student_id
            ],
            [
                'name' => $request->name,
                'class' => $request->class,
                'status' => $request->status
            ]
        );

        return response()->json(['success' => 'Student saved successfully.']);
    }

    public function edit($id)
    {
        $student = Student::find($id);
        return response()->json($student);
    }

    public function destroy($id)
    {
        Student::find($id)->delete();

        return response()->json(['success' => 'Student deleted successfully.']);
    }

    public function edit_status(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        if ($request->status == 'true') {
            $student->status = '1';
            $student->save();
        }
        if ($request->status == 'false') {
            $student->status = '0';
            $student->save();
        }

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}
