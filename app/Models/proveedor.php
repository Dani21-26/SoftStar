<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';
    public $incrementing = true;
    
    protected $fillable = [
        'nombre_empresa',
        'contacto_nombre',
        'telefono',
        'correo',
        'direccion',
        'estado'
    ];

    protected $attributes = [
        'estado' => 'activo' 
    ];


    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_proveedor', 'id_proveedor');
    }

    /**
     * Scope para proveedores activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo'); // Cambiado a string
    }
}