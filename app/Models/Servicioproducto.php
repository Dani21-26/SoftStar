<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioProducto extends Model
{
    protected $table = 'servicio_productos';
    
    protected $fillable = [
        'id_servicio',
        'id_producto',
        'cantidad'
    ];

    public function servicio()
    {
        return $this->belongsTo(ServicioTecnico::class, 'id_servicio');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}