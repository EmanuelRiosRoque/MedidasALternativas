<?php

namespace App\Livewire\Solicitud;

use App\Mail\InvitacionMediacion;
use App\Models\CatCancelacion;
use App\Models\Cja;
use App\Models\Correo;
use App\Models\Facilitador;
use App\Models\InvitacionSolicitante;
use App\Models\InvitacionInvitado;
use App\Models\Observacion;
use App\Models\Solicitante;
use App\Models\Solicitud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class InvitacionesPanel extends Component
{
    public ?Solicitud $solicitud = null;
    public ?int $solicitud_id    = null;
    public ?int $facilitador_id  = null;
    public ?int $tipo_proceso_id = null;
    public ?int $estatus_id      = null;

    /** ==== ESTRUCTURAS ==== */
    public $primera = [
        'detalle_solicitante' => [],
        'detalle_invitado' => [],
    ];

    public $segunda = [
        'detalle_solicitante' => [],
        'detalle_invitado' => [],
    ];

    public $cja = [
        'propuesta_inicio_fecha' => null,
        'propuesta_inicio_hora'  => null,
        'acepta_inicio'          => null,
        'fecha_vencimiento'      => null,
        'fecha_propuesta'        => null,
        'acepta_inicio_inv'      => null,
        // Mediacion -------------------
        'mediacion_fecha_inicio' => null, 
        'mediacion_hora_inicio'  => null,
        'mediacion_fecha_termino'=> null,
        'mediacion_hora_termino' => null,
        'fecha_envio_archivo'    => null,
        'conclucion_id'          => null,
        'persona_concluye_id'    => null,
    ];

    public $obs = [
        'fecha_observacion' => null,
        'observacion'       => null,
    ];

    public $solicitantes = [];
    public $invitados    = [];
    public $cat_cancelaciones    = [];
    public $facilitadores    = [];

    public function mount(Solicitud $solicitud)
    {
        $this->solicitud       = $solicitud;
        $this->solicitud_id    = $solicitud->id;
        $this->facilitador_id  = $solicitud->facilitador_id;
        $this->tipo_proceso_id = $solicitud->tipo_proceso_id;
        $this->estatus_id      = $solicitud->estatus_id;
        $this->cat_cancelaciones = CatCancelacion::all();
        $this->facilitadores = Facilitador::all();

        $this->solicitantes = Solicitante::where('solicitud_id', $solicitud->id)
            ->where('tipo_solicitante', 'solicitante')
            ->get();

        $this->invitados = Solicitante::where('solicitud_id', $solicitud->id)
            ->where('tipo_solicitante', 'invitado')
            ->get();

        $this->cargarInvitaciones();
    }

    /** ==================================================
     *  GUARDADO DE INVITACIONES MULTIPLES
     * ================================================== */
   public function guardarInvitaciones()
{
    DB::beginTransaction();

    try {
        $acciones = [];

        $colsBase = [
            'facilitador_id'  => $this->facilitador_id,
            'tipo_proceso_id' => $this->tipo_proceso_id,
            'estatus_id'      => $this->estatus_id,
        ];

        /** === GUARDAR PRIMERA INVITACIÓN === */
        foreach ($this->primera['detalle_solicitante'] as $solId => $data) {
            if (!is_numeric($solId) || empty($solId)) continue;

            InvitacionSolicitante::updateOrCreate(
                [
                    'solicitud_id'   => $this->solicitud_id,
                    'solicitante_id' => $solId,
                    'numero_inv'     => 1,
                ],
                array_merge($colsBase, $data)
            );
        }

        foreach ($this->primera['detalle_invitado'] as $invId => $data) {
            if (!is_numeric($invId) || empty($invId)) continue;

            InvitacionInvitado::updateOrCreate(
                [
                    'solicitud_id'   => $this->solicitud_id,
                    'solicitante_id' => $invId,
                    'numero_inv'     => 1,
                ],
                array_merge($colsBase, $data)
            );
        }

        /** === GUARDAR SEGUNDA INVITACIÓN === */
        foreach ($this->segunda['detalle_solicitante'] as $solId => $data) {
            if (!is_numeric($solId) || empty($solId)) continue;

            InvitacionSolicitante::updateOrCreate(
                [
                    'solicitud_id'   => $this->solicitud_id,
                    'solicitante_id' => $solId,
                    'numero_inv'     => 2,
                ],
                array_merge($colsBase, $data)
            );
        }

        foreach ($this->segunda['detalle_invitado'] as $invId => $data) {
            if (!is_numeric($invId) || empty($invId)) continue;

            InvitacionInvitado::updateOrCreate(
                [
                    'solicitud_id'   => $this->solicitud_id,
                    'solicitante_id' => $invId,
                    'numero_inv'     => 2,
                ],
                array_merge($colsBase, $data)
            );
        }

        /** === GUARDAR CJA === */
        if (collect($this->cja)->filter()->isNotEmpty()) {
            Cja::updateOrCreate(
                ['solicitud_id' => $this->solicitud_id],
                $this->cja
            );
        }

         

        DB::commit();
        $this->cargarInvitaciones();

        /** ===================================
         *  ENVÍO DE CORREOS (MODALIDAD EN LÍNEA)
         *  =================================== */

        if (isset($this->solicitud) && $this->solicitud->modalidad == 2) {
            try {
               // Obtener IDs
                $solicitanteIds = $this->getPersonaIds($this->solicitud_id, 'solicitante');
                $invitadoIds    = $this->getPersonaIds($this->solicitud_id, 'invitado');

                // Obtener correos asociados
                $correosSolicitantes = $this->getCorreosBySolicitantes($solicitanteIds);
                $correosInvitados    = $this->getCorreosBySolicitantes($invitadoIds);


                // dd($correosSolicitantes, $correosInvitados);
                // Enviar correos a solicitantes
                foreach ($correosSolicitantes as $correo) {
                    Mail::to($correo)->send(new InvitacionMediacion(
                        'Solicitante',
                        $this->cja['mediacion_enlace'] ?? 'https://meet.google.com/',
                        $this->cja['mediacion_hora_inicio'] ?? 'Sin definir',
                        $this->cja['mediacion_fecha_inicio'] ?? now()->format('d/m/Y'),
                        1,
                        1
                    ));
                }

                // Enviar correos a invitados
                foreach ($correosInvitados as $correo) {
                    Mail::to($correo)->send(new InvitacionMediacion(
                        'Invitado',
                        $this->cja['mediacion_enlace'] ?? 'https://meet.google.com/',
                        $this->cja['mediacion_hora_inicio'] ?? 'Sin definir',
                        $this->cja['mediacion_fecha_inicio'] ?? now()->format('d/m/Y'),
                        1,
                        2
                    ));
                }

                Toaster::success('Correos enviados correctamente a solicitantes e invitados.');
            } catch (\Exception $e) {
                Toaster::error('Error al enviar correos: ' . $e->getMessage());
            }
        }

        Toaster::success('Invitaciones guardadas correctamente.');
    } catch (\Throwable $e) {
        DB::rollBack();
        Toaster::error('Error al guardar: ' . $e->getMessage());
    }
}



    /** ==================================================
     *  CARGA DE DATOS POR SOLICITANTE E INVITADO
     * ================================================== */
    private function cargarInvitaciones(): void
    {
        $solicitudes = InvitacionSolicitante::where('solicitud_id', $this->solicitud_id)->get();
        $invitados   = InvitacionInvitado::where('solicitud_id', $this->solicitud_id)->get();

        $this->primera['detalle_solicitante'] = [];
        $this->primera['detalle_invitado'] = [];
        $this->segunda['detalle_solicitante'] = [];
        $this->segunda['detalle_invitado'] = [];

        foreach ($solicitudes as $s) {
            $datos = [
                'fecha_envio'      => $s->fecha_envio,
                'medio_envio'      => $s->medio_envio,
                'acepta_mediacion' => $s->acepta_mediacion,
            ];

            if ($s->numero_inv == 1) {
                $this->primera['detalle_solicitante'][$s->solicitante_id] = $datos;
            } elseif ($s->numero_inv == 2) {
                $this->segunda['detalle_solicitante'][$s->solicitante_id] = $datos;
            }
        }

        foreach ($invitados as $i) {
            $datos = [
                'fecha_sele_espera'    => $i->fecha_sele_espera,
                'hora_sele_espera'     => $i->hora_sele_espera,
                'atendio_sesion'       => $i->atendio_sesion,
                'fecha_asistencia'     => $i->fecha_asistencia,
                'hora_asistencia'      => $i->hora_asistencia,
                'acepta_mediacion_inv' => $i->acepta_mediacion_inv,
                'nombre'               => $i->nombre,
            ];

            if ($i->numero_inv == 1) {
                $this->primera['detalle_invitado'][$i->solicitante_id] = $datos;
            } elseif ($i->numero_inv == 2) {
                $this->segunda['detalle_invitado'][$i->solicitante_id] = $datos;
            }
        }

        if ($c = Cja::where('solicitud_id', $this->solicitud_id)->first()) {
            $this->cja = [
                'propuesta_inicio_fecha' => $c->propuesta_inicio_fecha,
                'propuesta_inicio_hora'  => $c->propuesta_inicio_hora,
                'acepta_inicio'          => $c->acepta_inicio,
                'fecha_vencimiento'      => $c->fecha_vencimiento,
                'fecha_propuesta'        => $c->fecha_propuesta,
                'acepta_inicio_inv'      => $c->acepta_inicio_inv,
                // Mediacion -------------------
                'mediacion_fecha_inicio' => $c->mediacion_fecha_inicio, 
                'mediacion_hora_inicio'  => $c->mediacion_hora_inicio,
                'mediacion_fecha_termino'=> $c->mediacion_fecha_termino,
                'mediacion_hora_termino' => $c->mediacion_hora_termino,
                'fecha_envio_archivo'    => $c->fecha_envio_archivo,
                'conclucion_id'          => $c->conclucion_id,
                'persona_concluye_id'    => $c->persona_concluye_id,
            ];
        }
    }

    /** ==================================================
     *  OBSERVACIONES
     * ================================================== */
    // public function agregarObservacion()
    // {
    //     $this->validate([
    //         'obs.fecha_observacion' => 'required|date',
    //         'obs.observacion'       => 'required|string|max:500',
    //     ]);

    //     Observacion::create([
    //         'solicitud_id'      => $this->solicitud_id,
    //         'fecha_observacion' => $this->obs['fecha_observacion'],
    //         'observacion'       => $this->obs['observacion'],
    //     ]);

    //     $this->obs = ['fecha_observacion' => null, 'observacion' => null];
    // }

    // public function borrarObservacion(int $id): void
    // {
    //     Observacion::where('solicitud_id', $this->solicitud_id)
    //         ->where('id', $id)
    //         ->delete();

    //     Toaster::success('Observación eliminada.');
    // }

       private function getPersonaIds(int $solicitudId, string $tipo = 'solicitante')
    {
        return Solicitante::where('solicitud_id', $solicitudId)
            ->where('tipo_solicitante', $tipo)
            ->pluck('id');
    }

    private function getCorreosBySolicitantes($solicitanteIds)
    {
        return Correo::whereIn('solicitante_id', $solicitanteIds)
            ->pluck('email');
    }
    public function render()
    {
        $observaciones = Observacion::where('solicitud_id', $this->solicitud_id)
            ->latest('fecha_observacion')
            ->get();

        return view('livewire.solicitud.invitaciones-panel', compact('observaciones'));
    }
}
