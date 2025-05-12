<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'    => 'required|string|max:255|unique:admins,username',
            'password'    => 'required|string|min:8|confirmed',
            'email'       => 'required|email|unique:admins,email',
            'no_telepon'  => 'nullable|string|max:20',
            'alamat'      => 'nullable|string|max:255',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        Admin::create($validated);

        return redirect()->route('index')
                         ->with('success', 'Admin berhasil ditambahkan');
    }

    public function show(Admin $admin)
    {
        return view('admin.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $rules = [
            'username'   => 'required|string|max:255|unique:admins,username,'.$admin->id_admin.',id_admin',
            'email'      => 'required|email|unique:admins,email,'.$admin->id_admin.',id_admin',
            'no_telepon' => 'nullable|string|max:20',
            'alamat'     => 'nullable|string|max:255',
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return redirect()->route('index')
                         ->with('success', 'Admin berhasil diupdate');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('index')
                         ->with('success', 'Admin berhasil dihapus');
    }
}
