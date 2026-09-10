<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('perfil.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validateWithBag('updatePerfil', [
            'name'   => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'avatar.image'  => 'El archivo debe ser una imagen.',
            'avatar.mimes'  => 'La foto debe ser JPG, PNG o WEBP.',
            'avatar.max'    => 'La foto no debe superar 2 MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Tu perfil se actualizó correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validateWithBag('updatePassword', [
            'password_actual' => ['required'],
            'password'        => ['required', 'confirmed', 'min:8'],
        ], [
            'password.confirmed' => 'La confirmación de la contraseña nueva no coincide.',
            'password.min'       => 'La contraseña nueva debe tener al menos 8 caracteres.',
        ]);

        if (! Hash::check($request->password_actual, $user->password)) {
            throw ValidationException::withMessages([
                'password_actual' => 'La contraseña actual no es correcta.',
            ])->errorBag('updatePassword');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Tu contraseña se actualizó correctamente.');
    }
}