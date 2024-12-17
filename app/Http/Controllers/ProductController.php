<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::paginate(16);

        $data = [
            'status' => 200,
            'products' => $product
        ];

        return response()->json($data,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|numeric|min:1',
                'stock' => 'required|integer|min:1',
                'image' => 'required|url',
            ]);

            $product = Product::create($validatedData);

            $data = [
                'status' => 200,
                'product' => $product,
            ];
            return response()->json($data,200);
        }catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if  (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }else{
            $data = [
                'status' => 200,
                'product' => $product
            ];
            return response()->json($data,200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $product = Product::find($id);
        if  (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }else{
            try {
                $validatedData = $request->validate([
                    'name' => 'nullable|string',
                    'description' => 'nullable|string',
                    'price' => 'nullable|numeric|min:1',
                    'stock' => 'nullable|integer|min:1',
                    'image' => 'nullable|url',

                ], [
                    'price.numeric' => 'The price must be a valid number.',
                    'stock.integer' => 'The stock must be a valid integer.',
                ]);

                $product->update($validatedData);
                $data = [
                    'status' => 200,
                    'product' => $product
                ];
                return response()->json($data, 200);
            } catch (ValidationException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $product = Product::find($id);
        if  (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }else{
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        }

    }
}
