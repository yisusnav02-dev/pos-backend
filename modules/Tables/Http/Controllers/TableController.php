<?php

namespace Modules\Tables\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Tables\Models\Table;
use Modules\Tables\Http\Requests\StoreTableRequest;
use Modules\Tables\Http\Requests\UpdateTableRequest;
use Modules\Tables\Http\Resources\TableResource;

class TableController extends Controller
{
    /**
     * Obtener todas las mesas
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Table::query();

            // Filtros
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            if ($request->has('location')) {
                $query->where('location', $request->location);
            }

            if ($request->has('min_capacity')) {
                $maxCapacity = $request->get('max_capacity');
                $query->byCapacity($request->min_capacity, $maxCapacity);
            }

            if ($request->has('search')) {
                $query->search($request->search);
            }

            // Ordenamiento
            if ($request->has('sort_field', 'sort_order')) {
                $sortField = $request->get('sort_field', 'sort_order');
                $sortDirection = $request->get('sort_direction', 'asc');
                $query->orderBy($sortField, $sortDirection);
            }

            // Paginación
            $tables = $query->paginate($request->get('per_page', 15));

            // Estadísticas
            $stats = [
                'total' => Table::count(),
                'available' => Table::available()->count(),
                'occupied' => Table::occupied()->count(),
                'reserved' => Table::reserved()->count(),
                'maintenance' => Table::underMaintenance()->count(),
            ];

            return response()->json([
                'data' => TableResource::collection($tables),
                'stats' => $stats,
                'meta' => [
                    'current_page' => $tables->currentPage(),
                    'per_page' => $tables->perPage(),
                    'total' => $tables->total(),
                    'last_page' => $tables->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las mesas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva mesa
     */
    public function store(StoreTableRequest $request): JsonResponse
    {
        try {
            $tableData = $request->all();

            $dataStore = [
                'name' => 'Mesa '.$tableData['table_id'],
                'code' => 'T'.$tableData['table_id'],
                'capacity' => 4,
                'status'  => 1, 
                'type' => 1
            ];

            $table = Table::create($dataStore);

            return response()->json([
                'message' => 'Mesa creada exitosamente',
                'data' => new TableResource($table)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la mesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una mesa específica
     */
    public function show($id): JsonResponse
    {
        try {
            $table = Table::find($id);

            if (!$table) {
                return response()->json([
                    'message' => 'Mesa no encontrada'
                ], 404);
            }

            return response()->json([
                'data' => new TableResource($table)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la mesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una mesa
     */
    public function update(UpdateTableRequest $request, $id): JsonResponse
    {
        try {
            $table = Table::find($id);

            if (!$table) {
                return response()->json([
                    'message' => 'Mesa no encontrada'
                ], 404);
            }

            $tableData = $request->validated();
            $table->update($tableData);

            return response()->json([
                'message' => 'Mesa actualizada exitosamente',
                'data' => new TableResource($table->fresh())
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la mesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una mesa (soft delete)
     */
    public function destroy($id): JsonResponse
    {
        try {
            $table = Table::find($id);

            if (!$table) {
                return response()->json([
                    'message' => 'Mesa no encontrada'
                ], 404);
            }

            // Verificar si la mesa está ocupada o reservada
            if ($table->isOccupied() || $table->isReserved()) {
                return response()->json([
                    'message' => 'No se puede eliminar una mesa que está ocupada o reservada'
                ], 422);
            }

            // $table->delete();

            // Borrado lógico
            $table->active = false;
            $table->deleted_at = now();
            $table->save();

            return response()->json([
                'message' => 'Mesa eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la mesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Cambiar estado de la mesa
     */
    public function updateStatus($id, Request $request): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:available,occupied,reserved,maintenance'
            ]);

            $table = Table::find($id);

            if (!$table) {
                return response()->json([
                    'message' => 'Mesa no encontrada'
                ], 404);
            }

            $table->update(['status' => $request->status]);

            $statusLabels = [
                'available' => 'disponible',
                'occupied' => 'ocupada',
                'reserved' => 'reservada',
                'maintenance' => 'en mantenimiento'
            ];

            return response()->json([
                'message' => "Mesa marcada como {$statusLabels[$request->status]}",
                'data' => new TableResource($table->fresh())
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cambiar el estado de la mesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener mesas disponibles
     */
    public function available(Request $request): JsonResponse
    {
        try {
            $query = Table::available();

            // Filtros adicionales para mesas disponibles
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            if ($request->has('location')) {
                $query->where('location', $request->location);
            }

            if ($request->has('min_capacity')) {
                $capacity = $request->min_capacity;
                $query->where('capacity', '>=', $capacity);
            }

            if ($request->has('max_capacity')) {
                $capacity = $request->max_capacity;
                $query->where('capacity', '<=', $capacity);
            }

            // Ordenamiento
            $sortField = $request->get('sort_field', 'capacity');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortField, $sortDirection);

            $tables = $query->get();

            return response()->json([
                'data' => TableResource::collection($tables),
                'count' => $tables->count(),
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las mesas disponibles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de mesas
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'total' => Table::count(),
                'available' => Table::available()->count(),
                'occupied' => Table::occupied()->count(),
                'reserved' => Table::reserved()->count(),
                'maintenance' => Table::underMaintenance()->count(),
                'by_type' => [
                    'indoor' => Table::byType('indoor')->count(),
                    'outdoor' => Table::byType('outdoor')->count(),
                    'terrace' => Table::byType('terrace')->count(),
                    'vip' => Table::byType('vip')->count(),
                ]
            ];

            return response()->json([
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}