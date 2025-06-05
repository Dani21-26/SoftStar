<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServicioTecnico extends Model
{
    protected $primaryKey = 'id_servicio';
    
    protected $fillable = [
        'codigo', 
        'cliente',
        'router',
        'litebean',
        'direccion',
        'falla_reportada',
        'estado',
        'id_empleado_toma',
        'id_empleado_confirma',
        'id_tecnico'
    ];

    /**
     * Relación con los productos utilizados en el servicio
     */
    public function productos(): HasMany
    {
        return $this->hasMany(ServicioProducto::class, 'id_servicio');
    }

    /**
     * Relación con el detalle técnico del servicio
     */
    // En app/Models/ServicioTecnico.php
    public function detalle()
    {
    return $this->hasOne(DetalleServicio::class, 'id_servicio', 'id_servicio');
    }

    /**
     * Obtiene el empleado que tomó el servicio
     */
    public function empleadoToma(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_empleado_toma');
    }
    
    /**
     * Obtiene el empleado que confirmó el servicio
     */
    public function empleadoConfirma(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_empleado_confirma');
    }

    /**
     * Obtiene el técnico asignado al servicio
     */
    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    /**
     * Scope para filtrar servicios por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para buscar servicios
     */
    public function scopeBuscar($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('codigo', 'like', '%'.$search.'%')
              ->orWhere('cliente', 'like', '%'.$search.'%')
              ->orWhere('direccion', 'like', '%'.$search.'%');
        });
    }
    
}