<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model {
    protected $table = 'solicitudes';
    protected $fillable = [
        'codigo', 'user_id', 'tipo_evento', 'invitados', 'zona',
        'locacion_id', 'fecha_evento', 'nombre', 'celular', 'correo',
        'comentarios', 'total_estimado', 'estado', 'motivo_estado'
    ];
    public function user() { return $this->belongsTo(User::class); }
    public function locacion() { return $this->belongsTo(Locacion::class); }
    public function lineas() { return $this->hasMany(LineaServicio::class, 'solicitud_id'); }
}
