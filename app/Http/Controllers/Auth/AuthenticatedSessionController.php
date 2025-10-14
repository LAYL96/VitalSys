<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar la vista de inicio de sesión.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar la solicitud de inicio de sesión.
     */
    public function store(Request $request)
    {
        //  Validar los datos ingresados por el usuario
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //  Intentar autenticar las credenciales
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        //  Regenerar la sesión para mayor seguridad
        $request->session()->regenerate();

        // Obtener el usuario autenticado
        $user = auth()->user();

        /**
         * Obtener el rol del usuario mediante Spatie
         * Spatie maneja los roles en una tabla intermedia y no como un campo en "users".
         * Por eso usamos getRoleNames()->first() para obtener el primer rol asignado.
         */
        $roleName = $user->getRoleNames()->first();

        /**
         * Redirigir al usuario según su rol
         * Cada rol tiene su propio destino al iniciar sesión.
         */
        switch ($roleName) {
            case 'Administrador':
                return redirect()
                    ->route('admin.dashboard')
                    ->with('welcome', 'Hola ' . $user->name . ', bienvenido al panel de administrador!');

            case 'Empleado':
                return redirect()
                    ->route('empleado.dashboard')
                    ->with('welcome', 'Hola ' . $user->name . ', bienvenido a tu panel de empleado!');

            case 'Médico':
                return redirect()
                    ->route('medico.dashboard')
                    ->with('welcome', 'Hola Dr. ' . $user->name . ', listo para atender a tus pacientes.');

            case 'Cliente':
                return redirect()
                    ->route('home')
                    ->with('welcome', 'Hola ' . $user->name . ', bienvenido a nuestra tienda en línea!');

            default:
                // Rol no definido o inesperado → enviar al inicio
                return redirect()
                    ->route('home')
                    ->with('welcome', 'Bienvenido de nuevo ' . $user->name . '!');
        }
    }

    /**
     * Cerrar sesión de usuario.
     */
    public function destroy(Request $request)
    {
        // Cerrar sesión
        Auth::guard('web')->logout();

        // Invalidar la sesión actual
        $request->session()->invalidate();

        // Regenerar el token CSRF
        $request->session()->regenerateToken();

        // Redirigir al inicio
        return redirect('/');
    }
}
