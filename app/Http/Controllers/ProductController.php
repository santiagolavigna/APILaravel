<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Helpers\Sender;

use Illuminate\Http\Request;


class ProductController extends Controller
{
    private $limit = 1;
    private $offset = 10;

    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $this->offset = $request->query('offset', 1);
        $this->limit = $request->query('limit', 10);

        $products = Product::where('activo',1)
        ->whereHas('category')
        ->orderBy('id', 'asc')
        ->offset($this->offset)
        ->limit($this->limit)
        ->get();

        return Sender::success(null, ProductResource::collection($products));
    }

    public function indexBO(Request $request)
    {
        $this->offset = $request->query('offset', 1);
        $this->limit = $request->query('limit', 10);

        $products = Product::whereHas('category')
        ->orderBy('id', 'asc')
        ->offset($this->offset)
        ->limit($this->limit)
        ->get();

        return Sender::success(null, ProductResource::collection($products));
    }

    public function indexCategory($id)
    {
        $products = Product::where('id_categoria',$id)
        ->whereHas('category')
        ->get();

        if($products->isEmpty()){
            return Sender::error('Products with category ' . $id .' not founded', null, 404);
        }

        return Sender::success(null, ProductResource::collection($products));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $products = Product::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return Sender::success('Product created successfully', $products, 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return Sender::error('Product not found', null, 404);
        }

        return Sender::success(null, ProductResource::make($product));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'categoria' => 'required|integer',
            'codigo' => 'required|integer',
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:200',
            'hasPreviousImage' => 'nullable|string',
            'namePicture' => 'required|string|max:2048',
            'precio' => 'required',
            'activo' => 'required|boolean',
            'mas_vendido' => 'required|boolean'
        ]);

        $imageString = "";

        if ($request->has('hasPreviousImage') && $request->input('hasPreviousImage') === "true") {
            $imageString = $this->addSemicolon($request->input('namePicture'));
        }
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                if ($file->isValid()) {
                    $name = $request->input('id') . "-" . rand(1, 5000) . "-" . $file->getClientOriginalName();
                    $uploadDir = 'Images/';
                    $uploadPath = public_path($uploadDir);
                    $uploadFile = $uploadPath . $name;
            
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
            
                    if (!move_uploaded_file($file->getPathname(), $uploadFile)) {
                        throw new \Exception("Ha ocurrido un error al subir la imagen.");
                    }
            
                    $imageString .= $name . ";";
                }
            }
        }
    
        $request->merge(['image_name' => $imageString]);

        $product = Product::find($id);
        if (!$product) {
            return Sender::error('Product not found', null, 404);
        }

        $product->nombre = $request->nombre;
        $product->descripcion = $request->descripcion;
        $product->id_categoria = $request->categoria;
        $product->codigo = $request->codigo;
        $product->imagen = $request->image_name;
        $product->precio = $request->precio;
        $product->activo = $request->activo;
        $product->mas_vendido = $request->mas_vendido;

        $product->save();

        return Sender::success('product updated successfully', ProductResource::make($product));
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return Sender::error('Product not found', null, 404);
        }

        $product->delete();

        return Sender::success('Product deleted successfully', null);
    }

    public function quantity()
    {
        $count = Product::count();

        return Sender::success('Total products count: ' . $count, $count);
    }

    public function find(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $nameLowerCase = strtolower($request->name);
        $nameUpperCase = strtoupper($request->name);

        $product = Product::where('activo', 1)
        ->where(function ($query) use ($nameLowerCase, $nameUpperCase) {
            $query->where('nombre', 'LIKE', '%' . $nameLowerCase . '%')
                ->orWhere('nombre', 'LIKE', '%' . $nameUpperCase . '%');
        })
        ->get();

        if($product->isEmpty()){
            return Sender::error('No results founded.', null, 404);
        }

        return Sender::success(null, ProductResource::collection($product));
    }

    function addSemicolon($string) {
        if (substr($string, -1) !== ';') {
            $string .= ';';
        }
        return $string;
    }
}
