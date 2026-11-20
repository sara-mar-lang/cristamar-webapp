<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LineaServicio extends Model {
    protected $table = 'linea_servicios';
    protected $fillable = ['solicitud_id', 'tarifa_id', 'cantidad', 'subtotal'];
    public function tarifa() { return $this->belongsTo(Tarifa::class); }
}
