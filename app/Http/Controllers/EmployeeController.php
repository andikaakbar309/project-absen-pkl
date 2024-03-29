<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {   
        return view('app.employee.index');
    }

    public function create()
    {
        return view('app.employee.form');
    }

    public function edit($id)
    {
        $data = Employee::find($id);

        return view('app.employee.form', compact('data'));
    }

    public function store(Request $request)
    {
        $isEdit = $request->id ? true : false;

        $rules = [
            'name' => 'required',
            'position' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'required',
            'phone_number' => 'required',
        ];

        $request->validate($rules);
        $imageUrl = null;

        if ($request->hasFile('photo')) {
            $imageUrl = $request->file('photo')->store('photo');
        } else {
            $imageUrl = $isEdit ? $request->old_photo : null;
        }

        $data = [
            'name' => $request->name,
            'position' => $request->position,
            'photo' => $imageUrl,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ];

        if (!$isEdit) {
            $result = Employee::create($data);
        } else {
            $result = Employee::find($request->id)->update($data);

            if (!$result) {
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Gagal update data Pegawai',
                ])->withInput();
            }
        }

        return redirect()->route('employee.index')->with([
            'status' => 'success',
            'message' => $isEdit ? 'Berhasil edit data Pegawai' : 'Berhasil menambah data Pegawai',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found.',
            ]);
        }

        $employee->update(['is_deleted' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully deleted Employee.',
        ]);
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = Employee::where('is_deleted', false)->latest()->get();
    
            return DataTables::of($data)
                ->make(true);
        }
    }
}
