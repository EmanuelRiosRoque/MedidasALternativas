<?php

namespace App\Http\Controllers\PDFs;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class PDFsController extends Controller
{
    private function datosBase()
    {
        Carbon::setLocale('es');
        $fechaActual = Carbon::now();

        return [
            'fecha' => $fechaActual->format('d/m/Y'),
            'dia' => $fechaActual->format('d'),
            'mes' => $fechaActual->translatedFormat('F'),
            'ultimoDigitoAnio' => substr($fechaActual->format('Y'), -1),
            'curso' => 'Introducción a Laravel',
        ];
    }

    public function amparo()
    {
        $pdf = Pdf::loadView('pdfs.amparo', $this->datosBase());
        $pdf->setPaper('letter', 'portrait');
        return $pdf->download('amparo.pdf');
    }

    public function correoMexico()
    {
        $pdf = Pdf::loadView('pdfs.correosDeMx', $this->datosBase());
        $pdf->setPaper([0, 0, 600, 249], 'portrait');
        return $pdf->download('correosDeMx.pdf');
    }
    
    public function servicioPostal()
    {
        $pdf = Pdf::loadView('pdfs.serviciosPostal', $this->datosBase());
        $pdf->setPaper([0, 0, 1300, 630], 'portrait');
        return $pdf->download('serviciosPostal.pdf');
    }


    public function amparoRepre()
    {
        $pdf = Pdf::loadView('pdfs.amparoRepresentante', $this->datosBase());
        $pdf->setPaper('letter', 'portrait');
        return $pdf->download('amparoRepresentante.pdf');
    }



}
