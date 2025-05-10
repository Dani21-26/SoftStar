<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nombre',
        'detalle',
        'stock',
        'id_categoria',
        'ubicacion',
        'precio',
        'id_proveedor',
        'estado'
    ];

    // Añade esto para asegurar el formato numérico
    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'id_categoria' => 'integer',
        'id_proveedor' => 'integer'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }


    /**
     * Scope para productos con stock bajo
     */
    public function scopeStockBajo($query, $minimo = 5)
    {
        return $query->where('stock', '<=', $minimo);
    }

    /**
     * Accessor para el precio formateado
     */
    public function getPrecioFormateadoAttribute()
    {
        return '$' . number_format($this->precio, 2);
    }

    /**
     * Verifica si el stock es bajo
     */
    public function getStockBajoAttribute()
    {
        return $this->stock <= 5; 
    }
}