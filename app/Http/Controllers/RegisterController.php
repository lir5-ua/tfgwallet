<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
public function showRegistrationForm()
{
    return view('sign-up');
}

public function register(Request $request)
{
$request->validate([
'name'     => 'required|string|max:255',
'email'    => 'required|string|email|max:255|unique:users',
'password' => 'required|string|min:8|confirmed',
]);

$user = User::create([
'name'     => $request->name,
'email'    => $request->email,
'password' => Hash::make($request->password),
'is_admin' => false,
]);

event(new Registered($user));

    return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, revisa tu correo para verificar tu cuenta.');
}
}
