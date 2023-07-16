<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'id_categoria',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'activo'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_categoria', 'id');
    }
}
