<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Helpers\Sender;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    public function index()
    {
        $categories = Category::all();
        return Sender::success(null, CategoryResource::collection($categories));
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return Sender::error('Category not found', null, 404);
        }

        return Sender::success(null, CategoryResource::make($category));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $category = Category::find($id);
        if (!$category) {
            return Sender::error('Category not found', null, 404);
        }

        $category->nombre = $request->nombre;
        $category->save();

        return Sender::success('category updated successfully', CategoryResource::make($category));
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return Sender::error('Category not found', null, 404);
        }

        $category->delete();

        return Sender::success('Category deleted successfully', null);
    }

    public function quantity()
    {
        $count = Category::count();

        return Sender::success('Total categories count: ' . $count, $count);
    }
}
