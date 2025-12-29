<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Modules\Users\Http\Requests\StoreUserRequest;
use Modules\Users\Http\Requests\UpdateUserRequest;
use Modules\Users\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Obtener todos los usuarios
     */
    public function index(Request $request)
    {
        /*
            {
                "search": "juan",
                "role": "waiter",
                "status": "active",
                "per_page": 10,
                "sort_field": "name",
                "sort_direction": "asc"
            }
        */

        try {
            $query = User::query();

            // Filtros
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
                });
            }

            // Ordenamiento
            $sortField = $request->get('sort_field', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            // Paginación
            $users = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'data' => UserResource::collection($users),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'last_page' => $users->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(StoreUserRequest $request)
    {
        /*
            {
                "name": "Juan Pérez",
                "email": "juan@restaurant.com",
                "phone_number": "+51987654321",
                "password": "password123",
                "password_confirmation": "password123",
                "role": "waiter"
            }

        */

        try {
            $userData = $request->validated();

            if ($request->has('password')) {
                $userData['password'] = Hash::make($userData['password']);
            }

            $user = User::create($userData);

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'data' => new UserResource($user)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un usuario específico
     */
    public function show($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'data' => new UserResource($user)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un usuario
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $userData = $request->validated();

            if (isset($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }

            $user->update($userData);

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'data' => new UserResource($user->fresh())
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un usuario (soft delete)
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // No permitir eliminarse a sí mismo
            if ($user->id === auth()->id()) {
                return response()->json([
                    'message' => 'No puedes eliminar tu propio usuario'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'message' => 'Usuario eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar estado del usuario (active/inactive)
     */
    public function updateStatus($id): JsonResponse
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // No permitir desactivarse a sí mismo
            if ($user->id === auth()->id()) {
                return response()->json([
                    'message' => 'No puedes desactivar tu propio usuario'
                ], 403);
            }

            $newStatus = $user->status ? false : 'active';
            $user->update(['status' => $newStatus]);

            return response()->json([
                'message' => "Usuario {$newStatus} exitosamente",
                'data' => new UserResource($user->fresh())
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cambiar el estado del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener lista de roles disponibles
     */
    public function getRoles(): JsonResponse
    {
        return response()->json([
            'data' => [
                ['value' => 1, 'label' => 'Administrador'],
                ['value' => 2, 'label' => 'Gerente'],
                ['value' => 3, 'label' => 'Mesero'],
            ]
        ]);
    }
}