<?php

namespace Modules\Comanda\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Comanda\Models\Comanda;
use Modules\Comanda\Models\ComandaItem;
use Modules\Tables\Models\Table;
use Modules\Comanda\Http\Resources\ComandaResource;

class ComandaController extends Controller
{
    /**
     * Obtener todas las comandas
     */
    public function index(Request $request)
    {
        try {
            $query = Comanda::with('items.product');

            // Filtros
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('table_id')) {
                $query->where('table_id', $request->table_id);
            }

            // Ordenamiento
            $sortField = $request->get('sort_field', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            // Paginación
            $comandas = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'data' => ComandaResource::collection($comandas),
                'meta' => [
                    'current_page' => $comandas->currentPage(),
                    'per_page' => $comandas->perPage(),
                    'total' => $comandas->total(),
                    'last_page' => $comandas->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las comandas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva comanda
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $dataComanda = [
                'number'       => $data['number'],   
                'table_id' => $data['table_id'],
                'mesero'   => $data['waiter_name'],
                'comensales' => $data['guest_count'],
                'status'   => 1,
                'total'    => 0
            ];

            $comanda = Comanda::create($dataComanda);

            $table = Table::find($id);
            $table->status = 2;
            $table->save();

            return response()->json([
                'message' => 'Comanda creada exitosamente',
                'data' => new ComandaResource(
                    $comanda->fresh()->load('items.product')
                )
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la comanda',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una comanda específica
     */
    public function show($table, $comanda)
    {
        try {
            $query = Comanda::with('items.product')
                ->where('status', 1);

            if ($comanda == 0) {
                $query->where('table_id', $table);
            } else {
                $query->where('id', $comanda);
            }

            $comanda = $query->first();

            if (!$comanda) {
                return response()->json([
                    'data' => null,
                    'message' => 'Comanda no encontrada'
                ]);
            }

            return response()->json([
                'data' => new ComandaResource($comanda)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la comanda',
                'error' => $e->getMessage()
            ], 500);
        }

    }


    /**
     * Cambiar estado de la comanda
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $comanda = Comanda::find($id);

            if (!$comanda) {
                return response()->json([
                    'message' => 'Comanda no encontrada'
                ], 404);
            }

            $comanda->update([
                'status' => $request->status
            ]);

            return response()->json([
                'message' => 'Estado actualizado correctamente',
                'data' => new ComandaResource(
                    $comanda->fresh()->load('items.product')
                )
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el estado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una comanda
     */
    public function destroy($id): JsonResponse
    {
        try {
            $comanda = Comanda::find($id);

            if (!$comanda) {
                return response()->json([
                    'message' => 'Comanda no encontrada'
                ], 404);
            }

            $comanda->delete();

            return response()->json([
                'message' => 'Comanda eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la comanda',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
