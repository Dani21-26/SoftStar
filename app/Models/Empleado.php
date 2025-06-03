<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{ 
    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    protected $fillable = ['id_empleado','nombre', 'cargo', 'ubicacion', 'telefono', 'correo'];
    public function detallesServicios()
{
    return $this->hasMany(DetalleServicio::class, 'id_empleado');
}
}
