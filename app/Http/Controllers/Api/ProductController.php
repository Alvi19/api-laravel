<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $products = Product::query();

            if ($request->has('name')) {
                $products->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->has('description')) {
                $products->where('description', 'like', '%' . $request->description . '%');
            }

            // Paginate results
            $products = $products->paginate(10);

            return response()->json($products);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch products', 'message' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $data = $request->input('q');

            $brand = Product::where('name', 'like', "%$data%")
                ->orWhere('description', 'like', "%$data%")
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Search Data Product',
                'data' => $brand
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to search products', 'message' => $e->getMessage()], 500);
        }
    }

    public function paginateData(Request $request)
    {
        try {
            $perPage = $request->has('per_page') ? (int)$request->per_page : 10;
            $products = Product::paginate($perPage);

            return response()->json($products);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to paginate products', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // Create product
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
            ]);

            return response()->json($product, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create product', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data Product',
            'data' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'price' => 'string'
        ]);

        try {
            $product->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Update Data product',
                'data' => $product
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data product'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Hapus Data Brand'
        ]);
    }
}
