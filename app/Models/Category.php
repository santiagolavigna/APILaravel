<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = ['nombre', 'descripcion'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
