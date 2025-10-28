<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Listado con filtros opcionales: q (texto) y role (nombre del rol).
     * Eager-load de roles para evitar N+1.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        // Filtro por texto (nombre o email)
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        // Filtro por rol (Spatie). Debe ser NOMBRE del rol.
        if ($request->filled('role')) {
            $query->role($request->input('role'));
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();

        // Para combos/filtros en la vista (puedes usar name o id según prefieras)
        $allRoles = Role::orderBy('name')->pluck('name', 'id'); // ['id' => 'name']

        return view('admin.users.index', compact('users', 'allRoles'));
    }

    /**
     * Form de creación: enviamos lista de roles.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get(); // id + name
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Crear usuario y asignar rol con Spatie.
     * Acepta role_id desde el formulario y lo convierte a nombre.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id); // recupera el rol elegido

        $user = new User();
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        // ⚠️ No uses $user->role_id = ... con Spatie
        $user->save();

        // Asignar rol por NOMBRE (Spatie)
        $user->assignRole($role->name);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Editar: enviamos usuario y lista de roles.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Actualizar usuario y sincronizar rol con Spatie.
     * Acepta role_id desde el formulario y lo convierte a nombre.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role_id'  => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $role = Role::findOrFail($request->role_id);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // ⚠️ No guardes role_id en users
        $user->save();

        // Sincronizar roles por NOMBRE (reemplaza roles anteriores)
        $user->syncRoles([$role->name]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario (con protección para no auto-eliminarse).
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
