<?php

namespace Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Products\Models\Product;
use Modules\Products\Models\Category;
use Modules\Products\Models\ProductWarehouse;
use Modules\Products\Http\Requests\StoreProductRequest;
use Modules\Products\Http\Requests\UpdateProductRequest;
use Modules\Products\Http\Requests\StoreCategoryRequest;
use Modules\Products\Http\Resources\ProductResource;
use Modules\Products\Http\Resources\CategoryResource;

class ProductController extends Controller
{
    /**
     * Obtener todos los productos
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with('category');

            // Filtros
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('availability')) {
                $query->where('availability', $request->availability);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('low_stock') && $request->low_stock) {
                $query->lowStock();
            }

            if ($request->has('search')) {
                $query->search($request->search);
            }

            // Ordenamiento
            $sortField = $request->get('sort_field', 'name');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortField, $sortDirection);

            // Paginación
            $products = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'data' => ProductResource::collection($products),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'last_page' => $products->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo producto
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $tableData = $request->all();

            $dataProducts = [
                'name' => $tableData['name'],
                'description' => 'Descripción '.$tableData['name'],
                'price' => $tableData['price'],
                'category_id' => $tableData['category']
            ];

            $product = Product::create($dataProducts);

            $dataProductsWharehouse = [
                'product_id' => $product->id,
                'warehouse_id' => 1,
                'stock' => 0,
                'min_stock' => 0,
                'max_stock' => 100,
            ]; 

            $productWharehouse = ProductWarehouse::create($dataProductsWharehouse);

            return response()->json([
                'message' => 'Producto creado exitosamente',
                'data' => new ProductResource($product)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un producto específico
     */
    public function show($id)
    {
        try {
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            return response()->json([
                'data' => new ProductResource($product)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un producto
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $productData = $request->validated();
            $product->update($productData);

            return response()->json([
                'message' => 'Producto actualizado exitosamente',
                'data' => new ProductResource($product->fresh()->load('category'))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un producto (soft delete)
     */
    public function destroy($id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }


            // $product->delete();

            // Borrado lógico
            $product->status = false;
            $product->deleted_at = now();
            $product->save();

            return response()->json([
                'message' => 'Producto eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la mesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar disponibilidad del producto
     */
    public function updateAvailability($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $newAvailability = $product->availability === 'available' ? 'unavailable' : 'available';
            $product->update(['availability' => $newAvailability]);

            return response()->json([
                'message' => "Producto {$newAvailability} exitosamente",
                'data' => new ProductResource($product->fresh()->load('category'))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cambiar la disponibilidad del producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las categorías
     */
    public function getCategories()
    {
        try {
            $categories = Category::withCount('products')
                ->active()
                ->ordered()
                ->get();

            return response()->json([
                'data' => CategoryResource::collection($categories)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las categorías',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva categoría
     */
    public function storeCategory(StoreCategoryRequest $request)
    {
        try {
            $categoryData = $request->validated();
            $category = Category::create($categoryData);

            return response()->json([
                'message' => 'Categoría creada exitosamente',
                'data' => new CategoryResource($category)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}