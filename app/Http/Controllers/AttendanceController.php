<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {   
        return view('app.attendance.index');
    }

    public function create()
    {
        return view('app.attendance.form');
    }

    public function edit($id)
    {
        $data = Attendance::find($id);

        return view('app.attendance.form', compact('data'));
    }

    public function store(Request $request)
    {
        $isEdit = $request->id ? true : false;

        $rules = [
            'name' => 'required',
            'date' => 'required',
            'position' => 'required',
            'status' => 'required',
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'reasons' => 'nullable'
        ];

        $request->validate($rules);
        $imageUrl = null;

        if ($request->hasFile('file')) {
            $imageUrl = $request->file('file')->store('file');
        } else {
            $imageUrl = $isEdit ? $request->old_file : null;
        }

        $data = [
            'name' => $request->name,
            'position' => $request->position,
            'date' => $request->date,
            'file' => $imageUrl,
            'status' => $request->status,
            'reasons' => $request->reasons,
        ];

        if (!$isEdit) {
            $result = Attendance::create($data);
        } else {
            $result = Attendance::find($request->id)->update($data);

            if (!$result) {
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Gagal update data Pegawai',
                ])->withInput();
            }
        }

        return redirect()->route('attendance.index')->with([
            'status' => 'success',
            'message' => $isEdit ? 'Berhasil edit data Pegawai' : 'Berhasil menambah data Pegawai',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $Attendance = Attendance::find($id);

        if (!$Attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Attendance not found.',
            ]);
        }

        $Attendance->update(['is_deleted' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully deleted Attendance.',
        ]);
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = Attendance::where('is_deleted', false)->latest()->get();
    
            return DataTables::of($data)
                ->make(true);
        }
    }
}
