<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleServicio extends Model
{
    protected $table = 'detalle_servicios';
    protected $fillable = [
        'id_servicio',
        'user_id', 
        'nota',
        'productos_utilizados'
    ];
    
    protected $casts = [
        'productos_utilizados' => 'array'
    ];
    
    public function servicio(): BelongsTo
    {
        return $this->belongsTo(ServicioTecnico::class, 'id_servicio');

    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    
}