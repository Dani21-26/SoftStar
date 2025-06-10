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
        'stock', // Ahora es string (ej: "100 metros")
        'id_categoria',
        'ubicacion',
        'precio', // Almacenado en COP (puede ser millones)
        'id_proveedor',
        'estado'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        // Eliminamos el cast de stock a integer ya que ahora es string
        'id_categoria' => 'integer',
        'id_proveedor' => 'integer'
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function servicios()
    {
        return $this->hasMany(ServicioProducto::class, 'id_producto');
    }

    // Accesores
    public function getCantidadStockAttribute()
    {
        $parts = explode(' ', $this->stock);
        return (float)$parts[0];
    }

    public function getUnidadStockAttribute()
    {
        $parts = explode(' ', $this->stock);
        return $parts[1] ?? 'unidades';
    }

    public function getPrecioFormateadoAttribute()
    {
        if ($this->precio >= 1000000) {
            return '$' . number_format($this->precio / 1000000, 2) . 'M';
        } elseif ($this->precio >= 1000) {
            return '$' . number_format($this->precio / 1000, 1) . 'K';
        }
        return '$' . number_format($this->precio, 2);
    }

    public function getPrecioBaseAttribute()
    {
        if ($this->precio >= 1000000 && $this->precio % 1000000 == 0) {
            return $this->precio / 1000000;
        } elseif ($this->precio >= 1000 && $this->precio % 1000 == 0) {
            return $this->precio / 1000;
        }
        return $this->precio;
    }

    public function getUnidadPrecioAttribute()
    {
        if ($this->precio >= 1000000 && $this->precio % 1000000 == 0) {
            return 1000000;
        } elseif ($this->precio >= 1000 && $this->precio % 1000 == 0) {
            return 1000;
        }
        return 1;
    }

    public function getStockBajoAttribute()
    {
        return $this->cantidad_stock <= 5; 
    }

    // Scopes
    public function scopeStockBajo($query, $minimo = 5)
    {
        return $query->whereRaw("CAST(SUBSTRING_INDEX(stock, ' ', 1) AS DECIMAL) <= ?", [$minimo]);
    }

    public function scopeDeCategoria($query, $categoriaId)
    {
        return $query->where('id_categoria', $categoriaId);
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%$termino%")
                    ->orWhere('detalle', 'like', "%$termino%")
                    ->orWhere('ubicacion', 'like', "%$termino%")
                    ->orWhereHas('categoria', function($q) use ($termino) {
                        $q->where('nombre', 'like', "%$termino%");
                    })
                    ->orWhereHas('proveedor', function($q) use ($termino) {
                        $q->where('nombre_empresa', 'like', "%$termino%");
                    });
    }
}