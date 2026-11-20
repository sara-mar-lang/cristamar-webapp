<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Locacion extends Model {
    protected $table = 'locaciones';
    protected $fillable = ['nombre', 'imagen', 'capacidad_max', 'zona'];
}
