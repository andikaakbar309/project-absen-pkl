<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('app.user.index');
    }

    public function create()
    {
        return view('app.user.form');
    }

    public function edit($id)
    {
        $data = User::find($id);

        return view('app.user.form', compact('data'));
    }

    public function store(Request $request)
    {
        $isEdit = $request->id ? true : false;

        $rules = [
            'name' => 'required',
            'username' => 'required',
            'role' => 'required',
            'email' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'required',
        ];

        $request->validate($rules);
        $imageUrl = null;

        if ($request->hasFile('avatar')) {
            $imageUrl = $request->file('avatar')->store('avatar');
        } else {
            $imageUrl = $isEdit ? $request->old_avatar : null;
        }

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'email' => $request->email,
            'avatar' => $imageUrl,
            'password' => $request->password,
        ];

        if (!$isEdit) {
            $result = User::create($data);
        } else {
            $result = User::find($request->id)->update($data);

            if (!$result) {
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Gagal update data Pegawai',
                ])->withInput();
            }
        }

        return redirect()->route('user.index')->with([
            'status' => 'success',
            'message' => $isEdit ? 'Berhasil edit data Pegawai' : 'Berhasil menambah data Pegawai',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $User = User::find($id);

        if (!$User) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ]);
        }

        $User->update(['is_deleted' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully deleted User.',
        ]);
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('is_deleted', false)->latest()->get();
    
            return DataTables::of($data)
                ->make(true);
        }
    }
}
