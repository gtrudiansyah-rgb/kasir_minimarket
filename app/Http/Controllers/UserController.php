<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'no_hp'    => 'nullable|string|max:20',
            'role'     => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_hp' => 'nullable|string|max:20',
            'role'  => 'required',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() !== $user->id) {
            $user->delete();
        }

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}