<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleServicio extends Model
{
    protected $table = 'detalle_servicios';
    
    protected $fillable = [
        'codigo_servicio',
        'nombre_cliente',
        'tecnico_id',
        'producto_id',
        'nota_servicio',
        'fecha_inicio',
        'fecha_confirmacion'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_confirmacion' => 'datetime'
    ];

    public function tecnico()
    {
        return $this->belongsTo(Empleado::class, 'tecnico_id', 'id_empleado');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}