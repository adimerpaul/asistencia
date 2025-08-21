<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class DocenteController extends Controller
{
    public function index(Request $request)
    {
        $q = Docente::query()
            ->with(['user:id,username,email,role,docente_id'])
            ->orderBy('nombre');

        if ($s = $request->get('search')) {
            $q->where(function($sub) use ($s) {
                $sub->where('nombre', 'like', "%$s%")
                    ->orWhere('ci', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%");
            });
        }

        return $q->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'   => ['required','string'],
            'ci'       => ['required','string','unique:docentes,ci'],
            'email'    => ['nullable','email'],
            'telefono' => ['nullable','string'],

            // banderas y datos de usuario opcional
            'crear_usuario'       => ['nullable','boolean'],
            'usuario.username'    => ['nullable','string','min:3','max:60','unique:users,username'],
            'usuario.email'       => ['nullable','email','unique:users,email'],
            'usuario.password'    => ['nullable','string','min:6'],
            'usuario.role'        => ['nullable','string'],
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $docente = Docente::create([
                'nombre'   => $validated['nombre'],
                'ci'       => $validated['ci'],
                'email'    => $validated['email'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
            ]);

            // Crear usuario si corresponde
            if ($request->boolean('crear_usuario')) {
                $u = $request->input('usuario', []);

                $username = $u['username'] ?? Str::slug($docente->ci ?: $docente->nombre, '.');
                $email    = $u['email']    ?? $docente->email;
                $password = $u['password'] ?? 'Doc-' . Str::random(8);
                $role     = $u['role']     ?? 'Docente';

                $user = User::create([
                    'name'       => $docente->nombre,
                    'username'   => $username,
                    'email'      => $email,
                    'password'   => $password, // usa el cast hashed del modelo
                    'role'       => $role,
                    'docente_id' => $docente->id,
                ]);

                $docente->setRelation('user', $user);
                // Retornar la contraseña en claro sólo al crearse
                return response()->json([
                    'docente'  => $docente->load('user:id,username,email,role,docente_id'),
                    'password' => $password
                ], 201);
            }

            return response()->json($docente->load('user:id,username,email,role,docente_id'), 201);
        });
    }

    public function update(Request $request, Docente $docente)
    {
        $validated = $request->validate([
            'nombre'   => ['required','string'],
            'ci'       => ['required','string', Rule::unique('docentes','ci')->ignore($docente->id)],
            'email'    => ['nullable','email'],
            'telefono' => ['nullable','string'],

            'crear_usuario'       => ['nullable','boolean'],
            'usuario.username'    => ['nullable','string','min:3','max:60', Rule::unique('users','username')->ignore($docente->user?->id)],
            'usuario.email'       => ['nullable','email', Rule::unique('users','email')->ignore($docente->user?->id)],
            'usuario.password'    => ['nullable','string','min:6'],
            'usuario.role'        => ['nullable','string'],
        ]);

        return DB::transaction(function () use ($request, $docente, $validated) {
            $docente->update([
                'nombre'   => $validated['nombre'],
                'ci'       => $validated['ci'],
                'email'    => $validated['email'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
            ]);

            if ($request->boolean('crear_usuario')) {
                $u = $request->input('usuario', []);

                if ($docente->user) {
                    $docente->user->update([
                        'name'     => $docente->nombre,
                        'username' => $u['username'] ?? $docente->user->username,
                        'email'    => $u['email']    ?? $docente->user->email,
                        'role'     => $u['role']     ?? $docente->user->role,
                        // si vino password lo actualizamos
                        ...(isset($u['password']) && $u['password'] ? ['password' => $u['password']] : []),
                    ]);
                } else {
                    // crear por primera vez
                    $username = $u['username'] ?? Str::slug($docente->ci ?: $docente->nombre, '.');
                    $email    = $u['email']    ?? $docente->email;
                    $password = $u['password'] ?? 'Doc-' . Str::random(8);
                    $role     = $u['role']     ?? 'Docente';

                    $user = User::create([
                        'name'       => $docente->nombre,
                        'username'   => $username,
                        'email'      => $email,
                        'password'   => $password,
                        'role'       => $role,
                        'docente_id' => $docente->id,
                    ]);
                    $docente->setRelation('user', $user);
                }
            }

            return response()->json($docente->load('user:id,username,email,role,docente_id'));
        });
    }

    public function destroy(Docente $docente)
    {
        // Si quieres eliminar soft el user también, puedes hacerlo aquí
        $docente->delete();
        return response()->json(['message' => 'Docente eliminado']);
    }

    // Extra: resetear contraseña del user vinculado
    public function resetPassword(Docente $docente)
    {
        if (!$docente->user) {
            return response()->json(['message' => 'El docente no tiene usuario asignado'], 422);
        }

        $new = 'Doc-' . Str::random(8);
        $docente->user->update(['password' => $new]);

        return response()->json(['message' => 'Contraseña reseteada', 'password' => $new]);
    }
}
