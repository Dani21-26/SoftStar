<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias'; // Nombre de la tabla
protected $primaryKey = 'id_categoria'; // Clave primaria personalizada
public $incrementing = true; // Asegúrate que sea autoincremental
    
    protected $fillable = [
        'nombre',
        
    ];
}
