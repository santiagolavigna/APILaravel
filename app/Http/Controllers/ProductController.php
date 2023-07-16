<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Helpers\Sender;

use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    public function index()
    {
        $products = Product::with('category')
        ->whereHas('category')
        ->where('activo',1)
        ->get();

        return Sender::success(null, ProductResource::collection($products));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return Sender::success('Product created successfully', $product, 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return Sender::error('Product not found', null, 404);
        }

        return Sender::success(null, $product);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $product = Product::find($id);
        if (!$product) {
            return Sender::error('Product not found', null, 404);
        }

        $product->title = $request->title;
        $product->description = $request->description;
        $product->save();

        return Sender::success('Product updated successfully', $product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return Sender::error('Product not found', null, 404);
        }

        $product->delete();

        return Sender::success('Product deleted successfully', null, 204);
    }
}
