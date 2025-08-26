<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facilitador extends Model
{
    protected $table = 'facilitadores'; // 👈 aquí forzamos el nombre correcto
    protected $fillable = [
        'tipo', 'nombre', 'materia', 'estudios', 'cedula', 'clave_certificacion', 'folio', 'clave_unica', 'fecha_certificacion',
        'vigencia_certificacion', 'tipo_domicilio', 'calle',
        'cp_solicitante', 'colonia', 'entidad_federativa_solicitante', 'municipio_solicitante',
        'fotografia', 
        
        'duracion_encargo', 'numero_renovaciones', 'area_adscrito',
        'autoridad_certificacion', 'especificacion_autoridad', 'clave_autoridad',
        'autorizacion', 'avale_autorizado', 'especializacion', 'avale_especializacion',
        'especializacion_arbitra', 'avale_autorizado_arbitra', 'convenios_suscritos',
        'convenios_ejecutados', 'procedimientos_quejas', 'tiene_resolucion',
        'avale_resolucion', 'infracciones', 'descripcion_sancion', 'cancelacion',
        'elementos_materiales', 'avale_materiales', 'quejas_recibidas', 'visitas_supervision',
        'fecha_supervision', 'video_supervision', 'juicio_amparo', 'fecha_publicacion',
        'publicacion_documento', 'dictamen_cja', 'avale_dictamen'
    ];

    public function correos()
    {
        return $this->hasMany(CorreoFacilitador::class);
    }

    public function telefonos()
    {
        return $this->hasMany(TelefonoFacilitador::class);
    }

    protected $casts = [
        'infracciones' => 'array',
    ];
}
