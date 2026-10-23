<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class UserController extends Controller
{
    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    public function indexInertia()
    {
        $this->ensureAdmin();

        return Inertia::render('Pengguna/Index', [
            'users' => User::with('jurusan')->orderBy('name')->get([
                'id', 'name', 'email', 'role', 'jurusan_id',
            ]),
            'jurusanList' => Jurusan::orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,jurusan'],
            'jurusan_id' => ['nullable', 'exists:jurusans,id', 'required_if:role,jurusan'],
        ]);

        $data['password'] = Hash::make($data['password']);
        if ($data['role'] === 'admin') {
            $data['jurusan_id'] = null;
        }

        User::create($data);

        return redirect()->route('pengguna')->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, int $id)
    {
        $this->ensureAdmin();

        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,jurusan'],
            'jurusan_id' => ['nullable', 'exists:jurusans,id', 'required_if:role,jurusan'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        if ($data['role'] === 'admin') {
            $data['jurusan_id'] = null;
        }

        $user->update($data);

        return redirect()->route('pengguna')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        $this->ensureAdmin();

        abort_if(auth()->id() === $id, 422, 'User yang sedang login tidak dapat dihapus');
        User::findOrFail($id)->delete();

        return redirect()->route('pengguna')->with('success', 'User berhasil dihapus');
    }

    public function passwordForm()
    {
        return Inertia::render('Pengguna/Password');
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('password.edit')->with('success', 'Password berhasil diubah');
    }

    public function resetPassword(Request $request, int $id)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::findOrFail($id)->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('pengguna')->with('success', 'Password user berhasil direset');
    }
}
