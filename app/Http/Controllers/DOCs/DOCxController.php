<?php

namespace App\Http\Controllers\DOCs;

use Carbon\Carbon;
use App\Models\Solicitante;
use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\TemplateProcessor;

class DOCxController extends Controller
{
    public function invitacion_uno($id)
    {
        return $this->renderInvitation($id, 'inv_uno.docx', 'invitacion-uno.docx');
    }

    public function invitacion_dos($id)
    {
        return $this->renderInvitation($id, 'inv_dos.docx', 'invitacion-dos.docx');
    }   

    public function invitacion_segui($fecha)
    {
        try {
            $templatePath = public_path('docs/seguimiento.docx');
            if (!file_exists($templatePath)) {
                return "Plantilla no encontrada en: $templatePath";
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

    // public function sobreSepomex($id)
    // {
    //     try {
    //         $templatePath = public_path('docs/sobreSepomex.docx');
    //         if (!file_exists($templatePath)) {
    //             return "Plantilla no encontrada en: $templatePath";
    //         }

    //         $personas  = Solicitante::where('solicitud_id', $id)
    //             ->where('tipo_solicitante', 'invitado')
    //             ->first(); 

    //         $nombreInv = $personas
    //             ? trim("{$personas->nombre} {$personas->apellido_p} {$personas->apellido_m}")
    //             : '—';

    //         $calle    = $personas->calle              ?? '—';
    //         $colonia  = $personas->colonia            ?? '—';
    //         $municipio= $personas->municipio          ?? '—';
    //         $cp  = $personas->cp ?? '—';

    //         $template = new TemplateProcessor($templatePath);

    //         $template->setValue('nombre',   $nombreInv);
    //         $template->setValue('calle',    $calle);
    //         $template->setValue('colonia',  $colonia);
    //         $template->setValue('alcaldía', $municipio);
    //         $template->setValue('cp',  $cp);

    //         $tmp = tempnam(sys_get_temp_dir(), 'PHPWord');
    //         $template->saveAs($tmp);

    //         return response()->download($tmp, 'sobre-sepomex.docx')->deleteFileAfterSend(true);

    //     } catch (\Throwable $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }

    // public function sobreSepomex($id)
    // {
    //     try {
    //         $templatePath = public_path('docs/sobreSepomex.docx');
    //         if (!file_exists($templatePath)) {
    //             return "❌ Plantilla no encontrada en: $templatePath";
    //         }

    //         // 🔹 Traer todos los invitados
    //         $invitados = Solicitante::where('solicitud_id', $id)
    //             ->where('tipo_solicitante', 'invitado')
    //             ->get();

    //         if ($invitados->isEmpty()) {
    //             return "⚠️ No hay invitados para esta solicitud.";
    //         }

    //         $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

    //         // 🔹 Clonar el bloque "sobres" tantas veces como invitados
    //         $template->cloneBlock('sobres', $invitados->count(), true, true);

    //         foreach ($invitados as $i => $inv) {
    //             $idx = $i + 1;

    //             $template->setValue("nombre#{$idx}",   trim("{$inv->nombre} {$inv->apellido_p} {$inv->apellido_m}"));
    //             $template->setValue("calle#{$idx}",    $inv->calle    ?? '—');
    //             $template->setValue("colonia#{$idx}",  $inv->colonia  ?? '—');
    //             $template->setValue("alcaldía#{$idx}", $inv->municipio?? '—');
    //             $template->setValue("cp#{$idx}",       $inv->cp       ?? '—');
    //         }

    //         $tmp = tempnam(sys_get_temp_dir(), 'PHPWord');
    //         $template->saveAs($tmp);

    //         return response()->download($tmp, 'sobres-sepomex.docx')->deleteFileAfterSend(true);

    //     } catch (\Throwable $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }

    public function sobreSepomex()
{
    try {
        $templatePath = public_path('docs/sobreSepomex.docx');
        if (!file_exists($templatePath)) {
            return "❌ Plantilla no encontrada en: $templatePath";
        }

        $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // 🔹 Forzar 3 sobres de ejemplo
        $numEjemplo = 3;
        $template->cloneBlock('sobres', $numEjemplo, true, true);

        for ($i = 1; $i <= $numEjemplo; $i++) {
            $template->setValue("nombre",   "Invitado de Prueba {$i}");
            $template->setValue("calle",    "Calle Falsa {$i}");
            $template->setValue("colonia",  "Colonia Test {$i}");
            $template->setValue("alcaldía", "Alcaldía Ejemplo");
            $template->setValue("cp",       "1234{$i}");
        }

        $tmp = tempnam(sys_get_temp_dir(), 'PHPWord');
        $template->saveAs($tmp);

        return response()->download($tmp, 'sobres-sepomex-ejemplo.docx')->deleteFileAfterSend(true);

    } catch (\Throwable $e) {
        return back()->with('error', $e->getMessage());
    }
}


    public function sobrePersonal($id)
    {
         try {
            $templatePath = public_path('docs/sobrePersonal.docx');
            if (!file_exists($templatePath)) {
                return "Plantilla no encontrada en: $templatePath";
            }

            $personas  = Solicitante::where('solicitud_id', $id)
                ->where('tipo_solicitante', 'solicitante')
                ->first();

            $nombreSoli = $personas
                ? trim("{$personas->nombre} {$personas->apellido_p} {$personas->apellido_m}")
                : '—';

            $template = new TemplateProcessor($templatePath);

            $template->setValue('nombre',   $nombreSoli);

            $tmp = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tmp);

            return response()->download($tmp, 'sobre-personal.docx')->deleteFileAfterSend(true);

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function renderInvitation($id, string $templateFile, string $downloadName)
    {
        try {
            $templatePath = public_path("docs/{$templateFile}");
            if (!file_exists($templatePath)) {
                return "Plantilla no encontrada en: $templatePath";
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
}
