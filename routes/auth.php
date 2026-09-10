<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas no son correctas.',
            ])->onlyInput('email');
        }

        // Cuenta desactivada por un admin/moderador: no debe poder entrar
        if (! Auth::user()->activo) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Tu cuenta está desactivada. Contacta a un administrador.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    });

    // ── Registro de nuevos usuarios ──
    Route::get('/registro', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/registro', function (Request $request) {
        $data = $request->validate([
    'name'     => ['required', 'string', 'max:255'],
    'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
    'password' => ['required', 'confirmed', 'min:8'],
    'terminos' => ['accepted'],
], [
    'email.unique' => 'Ya existe una cuenta registrada con ese correo.',
    'password.confirmed' => 'La confirmación de la contraseña no coincide.',
    'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
    'terminos.accepted' => 'Debes aceptar los Términos y Condiciones para crear una cuenta.',
]);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),
            'email_verified_at' => now(),
            'activo'            => true,
        ]);

        $user->assignRole('lector');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', '¡Bienvenido a Etnobotánica! Tu cuenta fue creada correctamente.');
    });
});

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');