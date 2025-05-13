<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicioTecnico extends Model
{
    protected $table = 'servicio_tecnicos';
    protected $primaryKey = 'id_servicio';
    
    protected $fillable = [
        'codigo',
        'cliente',
        'id_empleado',
        'id_producto',
        'falla_reportada',
        'estado'
    ];
    
    protected $casts = [
        'estado' => 'string'
    ];

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Generar código automático al crear (ST-0001)
            $latestId = self::max('id_servicio') + 1;
            $model->codigo = 'ST-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);
        });
    }
}