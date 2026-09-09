<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Pengecekan Akses Super Admin
     */
    private function checkSuperAdmin()
    {
        abort_if(
            !auth()->check() || strtolower(auth()->user()->role ?? '') !== 'super_admin',
            403,
            'Akses Ditolak: Hanya Super Admin yang diizinkan mengelola user.'
        );
    }

    public function index()
    {
        $this->checkSuperAdmin();

        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->checkSuperAdmin();

        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->checkSuperAdmin();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:super_admin,manager,kasir',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $this->checkSuperAdmin();

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkSuperAdmin();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:super_admin,manager,kasir',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $this->checkSuperAdmin();

        if (auth()->check() && auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}