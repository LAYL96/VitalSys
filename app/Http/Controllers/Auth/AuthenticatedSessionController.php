<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // Validar los datos de login
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar autenticación
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // Redirigir según el rol del usuario
        $user = auth()->user();

        // Asegurarnos que el usuario tenga un rol cargado
        $roleName = $user->role ? $user->role->name : null;

        switch ($roleName) {
            case 'Administrador':
                return redirect()
                    ->route('admin.dashboard')
                    ->with('welcome', 'Hola ' . $user->name . ', bienvenido al panel de administrador!');
            case 'Empleado':
                return redirect()
                    ->route('empleado')
                    ->with('welcome', 'Hola ' . $user->name . ', bienvenido a tu panel de empleado!');
            case 'Médico':
                return redirect()
                    ->route('medico')
                    ->with('welcome', 'Hola Dr. ' . $user->name . ', listo para atender a tus pacientes.');
            case 'Cliente':
                return redirect()
                    ->route('home')
                    ->with('welcome', 'Hola ' . $user->name . ', bienvenido a nuestra tienda en línea!');
            default:
                return redirect()
                    ->route('dashboard')
                    ->with('welcome', 'Bienvenido de nuevo ' . $user->name . '!');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
