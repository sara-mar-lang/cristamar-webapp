<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model {
    protected $table = 'tarifas';
    protected $fillable = ['nombre', 'tipo_cobro', 'precio', 'activa'];
}
