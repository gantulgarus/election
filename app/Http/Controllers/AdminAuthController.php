<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function index()
    {
        $users = User::paginate(25);
        return view('admin.users.index', compact('users'));
    }
    public function showLoginForm()
    {
        return view('admin.users.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_admin) {
                Auth::logout();
                return back()->withErrors(['email' => 'Энэ эрхээр админ нэвтрэх боломжгүй.']);
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Имэйл эсвэл нууц үг буруу байна.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function userVotes(User $user)
    {
        $votes = $user->votes()->with('candidate')->latest()->get();

        return view('admin.users.votes', compact('user', 'votes'));
    }

    // Хэрэглэгч үүсгэх форм
    public function create()
    {
        return view('admin.users.create');
    }

    // Хэрэглэгчийг хадгалах
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'is_admin' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin ?? false,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Хэрэглэгч амжилттай үүсгэгдлээ.');
    }

    // Хэрэглэгч засах форм
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Хэрэглэгчийг шинэчлэх
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'is_admin' => 'nullable|boolean',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->is_admin = $request->is_admin ?? false;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Хэрэглэгч амжилттай шинэчлэгдлээ.');
    }

    // Хэрэглэгч устгах
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Хэрэглэгч устгагдлаа.');
    }
}
