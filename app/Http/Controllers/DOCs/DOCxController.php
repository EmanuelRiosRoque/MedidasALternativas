<?php

namespace App\Http\Controllers\DOCs;

use Carbon\Carbon;
use App\Models\Solicitante;
use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\TemplateProcessor;

class DOCxController extends Controller
{
    // ✅ Endpoints mínimos que delegan en un método común
    public function invitacion_uno($id)
    {
        return $this->renderInvitation($id, 'inv_uno.docx', 'invitacion-uno.docx');
    }

    public function invitacion_dos($id)
    {
        return $this->renderInvitation($id, 'inv_dos.docx', 'invitacion-dos.docx');
    }

    private function renderInvitation($id, string $templateFile, string $downloadName)
    {
        try {
            $templatePath = public_path("docs/{$templateFile}");
            if (!file_exists($templatePath)) {
                return "❌ Plantilla no encontrada en: $templatePath";
            }

            $values = $this->buildPlaceholdersForSolicitud($id);

            $template = new TemplateProcessor($templatePath);
            foreach ($values as $key => $value) {
                $template->setValue($key, $value);
            }

            $tmp = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tmp);

            return response()->download($tmp, $downloadName)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function buildPlaceholdersForSolicitud($id): array
    {
        $personas = Solicitante::with(['telefonos', 'correos'])
            ->where('solicitud_id', $id)
            ->get()
            ->groupBy('tipo_solicitante'); // 'solicitante' | 'invitado'

        $invitado     = optional($personas->get('invitado'))->first();
        $solicitante  = optional($personas->get('solicitante'))->first();

        $nombreInv = $invitado
            ? trim("{$invitado->nombre} {$invitado->apellido_p} {$invitado->apellido_m}")
            : 'Sin invitado';
        $telInv = $invitado
            ? $invitado->telefonos->pluck('numero')->filter()->unique()->implode(', ')
            : '—';
        $mailInv = $invitado
            ? $invitado->correos->pluck('email')->filter()->unique()->implode(', ')
            : '—';

        $nombreSol = $solicitante
            ? trim("{$solicitante->nombre} {$solicitante->apellido_p} {$solicitante->apellido_m}")
            : 'Sin solicitante';
        $telSol = $solicitante
            ? $solicitante->telefonos->pluck('numero')->filter()->unique()->implode(', ')
            : '—';
        $mailSol = $solicitante
            ? $solicitante->correos->pluck('email')->filter()->unique()->implode(', ')
            : '—';
 
        return [
            'nombre_invitado'     => $nombreInv,
            'num_invitado'        => $telInv,
            'email_invitado'      => $mailInv,
            'nombre_solicitante'  => $nombreSol,
            'num_solicitante'     => $telSol,
            'email_solicitante'   => $mailSol,
        ];
    }

    public function invitacion_segui($fecha)
    {
        try {
            $templatePath = public_path('docs/seguimiento.docx');
            if (!file_exists($templatePath)) {
                return "❌ Plantilla no encontrada en: $templatePath";
            }

            $fechaCarbon = Carbon::parse($fecha)->addDay();
            $mesTexto    = $fechaCarbon->locale('es')->translatedFormat('F');

            $template = new TemplateProcessor($templatePath);
            $template->setValue('dia',  $fechaCarbon->format('d'));
            $template->setValue('mes',  ucfirst($mesTexto));
            $template->setValue('anio', $fechaCarbon->format('Y'));

            $tmp = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tmp);

            return response()->download($tmp, 'seguimiento.docx')->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
