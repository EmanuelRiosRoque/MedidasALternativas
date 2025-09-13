<?php

namespace App\Http\Controllers\DOCs;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Solicitante;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
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
public function manifestacionDocumento($id)
{
    try {
        // 1) Configurar renderer de PDF (DomPDF)
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

        // 2) Rutas de plantillas
        $tplSolicitante = public_path('docs/manifestacionSolicitantedocx.docx');
        $tplInvitado    = public_path('docs/manifestacionInvitadosdocx.docx');

        if (!file_exists($tplSolicitante) || !file_exists($tplInvitado)) {
            throw new \RuntimeException("No se encontraron las plantillas en /public/docs");
        }

        // 3) Helpers
        $fullName = function ($p) {
            return trim(sprintf('%s %s %s', $p->nombre ?? '', $p->apellido_p ?? '', $p->apellido_m ?? '')) ?: '—';
        };
        $slug = function ($s) {
            return preg_replace('/[^A-Za-z0-9_\-]/', '_', $s);
        };

        // 4) Consultas
        $solicitante = \App\Models\Solicitante::where('solicitud_id', $id)
            ->where('tipo_solicitante', 'solicitante')
            ->first();

        $invitados = \App\Models\Solicitante::where('solicitud_id', $id)
            ->where('tipo_solicitante', 'invitado')
            ->get();

        if (!$solicitante && $invitados->isEmpty()) {
            throw new \RuntimeException('No se encontraron personas en la solicitud.');
        }

        $nombreSolicitante = $solicitante ? $fullName($solicitante) : '—';

        // 5) Directorio temporal y ZIP
        $tmpDir = storage_path('app/tmp_manifestaciones_' . uniqid());
        if (!File::exists($tmpDir)) {
            File::makeDirectory($tmpDir, 0777, true);
        }

        $zipFile = storage_path("app/manifestaciones_{$id}.zip");
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('No se pudo crear el archivo ZIP.');
        }

        // 6) Función para convertir DOCX -> PDF
        $convertToPdf = function (string $docxPath, string $pdfPath) {
            // Carga DOCX
            $phpWord = IOFactory::load($docxPath, 'Word2007');

            // Crea writer PDF (usa el renderer configurado arriba)
            $writer = IOFactory::createWriter($phpWord, 'PDF');
            $writer->save($pdfPath);

            if (!file_exists($pdfPath) || filesize($pdfPath) === 0) {
                throw new \RuntimeException('Fallo al convertir a PDF: ' . basename($pdfPath));
            }
        };

        // 7) Documentos para cada invitado (PDF) — ambos nombres completos
        foreach ($invitados as $inv) {
            $nombreInvitado = $fullName($inv);
            $baseName       = $slug($nombreInvitado) . '_INVITADO';
            $docxPath       = "{$tmpDir}/{$baseName}.docx";
            $pdfPath        = "{$tmpDir}/{$baseName}.pdf";

            $template = new TemplateProcessor($tplInvitado);
            $template->setValue('nombre_solicitante', $nombreSolicitante);
            $template->setValue('nombre_invitado', $nombreInvitado);
            $template->saveAs($docxPath);

            $convertToPdf($docxPath, $pdfPath);
            $zip->addFile($pdfPath, "{$baseName}.pdf");
        }

        // 8) Documento del solicitante (PDF) — ambos nombres completos
        if ($solicitante) {
            $nombresInvitados = $invitados->map(fn ($inv) => $fullName($inv))->implode(', ');
            $baseName         = $slug($nombreSolicitante) . '_SOLICITANTE';
            $docxPath         = "{$tmpDir}/{$baseName}.docx";
            $pdfPath          = "{$tmpDir}/{$baseName}.pdf";

            $template = new TemplateProcessor($tplSolicitante);
            $template->setValue('nombre_solicitante', $nombreSolicitante);
            $template->setValue('nombre_invitado', $nombresInvitados ?: '—');
            $template->saveAs($docxPath);

            $convertToPdf($docxPath, $pdfPath);
            $zip->addFile($pdfPath, "{$baseName}.pdf");
        }

        // 9) Cerrar ZIP
        $zip->close();

        // 10) Limpiar temporales (DOCX/PDF) antes de enviar (el ZIP ya está cerrado)
        File::deleteDirectory($tmpDir);

        // 11) Descargar ZIP y borrarlo después de enviar
        return response()->download($zipFile)->deleteFileAfterSend(true);

    } catch (\Throwable $e) {
        // Si quieres ver el error exacto durante pruebas, descomenta:
        // dd($e->getMessage(), $e->getTraceAsString());

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
