<?php

namespace App\Traits\ConvenioTraits;

use Illuminate\Support\Facades\File;
use Illuminate\Http\File as HttpFile;
use App\Models\Catalogos\CatDocumento;
use App\Models\Catalogos\CatDocumentoCivil;
use App\Models\Catalogos\CatDocumentoFamiliar;
use App\Models\Catalogos\CatOcupacion;
use Illuminate\Support\Facades\Storage;
use App\Models\Catalogos\CatEscolaridad;
use App\Models\Catalogos\CatMedioDifusion;

trait HandleDocumentos
{

    public function ocupaciones()
    {
        return CatOcupacion::orderBy('nombre')->get(['id','nombre']);   
    }

    public function escolaridades()
    {
        return CatEscolaridad::orderBy('nombre')->get(['id','nombre']);   
    }

    public function mediosDifusion()
    {
        return CatMedioDifusion::orderBy('nombre')->get(['id','nombre']);
    }



    public function updatedTipo($value)
    {
        $this->cargarDocumentos(
            CatDocumentoCivil::class,
            'documentosOpcionales',
            'documentoSeleccionado',
            $value
        );
    }

    public function updatedTemaFamiliar($value)
    {
        $this->cargarDocumentos(
            CatDocumentoFamiliar::class,
            'documentosFamiliarOpcionales',
            'documentosFamiliarSeleccionado',
            $value
        );
    }

    public function updatedDocumentoSeleccionado($value)
    {
        $this->agregarDocumento('documentosCargados', 'documentoSeleccionado', $value);
    }

    public function updatedDocumentosFamiliarSeleccionado($value)
    {
        $this->agregarDocumento('documentosFamiliaresCargados', 'documentosFamiliarSeleccionado', $value);
    }

    /**
     * Carga documentos dinámicamente según modelo y propiedad
     */
    protected function cargarDocumentos(string $modelo, string $propOpcionales, string $propSeleccionado, string $valor): void
    {
        $this->{$propOpcionales} = $modelo::porTipo($valor);
        $this->{$propSeleccionado} = '';
    }

    /**
     * Agrega documentos a la lista evitando duplicados
     */
    protected function agregarDocumento(string $propCargados, string $propSeleccionado, string $valor): void
    {
        if (!in_array($valor, $this->{$propCargados})) {
            $this->{$propCargados}[] = $valor;
        }
        $this->{$propSeleccionado} = '';
    }


    public function guardarArchivos(): array
    {
        $documentosConArchivos = [];

        foreach ($this->documentosCargados as $index => $documentoTipo) {
            $archivo = $this->archivosSubidos[$index][0] ?? null;

            if ($archivo && isset($archivo['path']) && file_exists($archivo['path'])) {
                // Generar nombre único
                $nombreOriginal = $archivo['name'];
                $nuevoNombre = uniqid() . '_' . $nombreOriginal;
                $destino = 'documentos';

                // Guardar archivo en public/documentos/
                $rutaFinal = Storage::disk('public')->putFileAs(
                    $destino,
                    new HttpFile($archivo['path']),
                    $nuevoNombre
                );

                $rutaPublica = 'storage/' . $rutaFinal;

                // Guardar información del documento para base de datos
                $documentosConArchivos[] = [
                    'documento'        => $documentoTipo,
                    'nombre_original'  => $nombreOriginal,
                    'ruta'             => $rutaPublica,
                    'extension'        => $archivo['extension'] ?? pathinfo($nombreOriginal, PATHINFO_EXTENSION),
                    'size'             => $archivo['size'] ?? null,
                ];
            }
        }

        return $documentosConArchivos;
    }

    public function eliminarDocumentoFamiliar($doc)
    {
        if (($key = array_search($doc, $this->documentosFamiliaresCargados)) !== false) {
            unset($this->documentosFamiliaresCargados[$key]);
            unset($this->archivosFamiliaresSubidos[$key]);
            $this->documentosFamiliaresCargados = array_values($this->documentosFamiliaresCargados);
            $this->archivosFamiliaresSubidos = array_values($this->archivosFamiliaresSubidos);
        }
    }

    public function eliminarDocumento($doc)
    {
        if (($key = array_search($doc, $this->documentosCargados)) !== false) {
            unset($this->documentosCargados[$key]);
            unset($this->archivosSubidos[$key]);
            $this->documentosCargados = array_values($this->documentosCargados);
            $this->archivosSubidos = array_values($this->archivosSubidos);
        }
    }
}
