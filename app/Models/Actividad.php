<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    protected $table = 'actividads';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'fecha_inicio',
        'fecha_fin'
    ];
    //
}
