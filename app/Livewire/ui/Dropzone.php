<?php

namespace App\Livewire\Ui;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Modelable;
use Illuminate\Support\Facades\Storage;

class Dropzone extends Component
{
    use WithFileUploads;

    // ← HAZLA NULLABLE
    #[Modelable] public ?array $files = null;   // [['path','name','size','mime']]
    public array $uploads = [];                 // TemporaryUploadedFile[]

    // Config
    public string $disk = 'public';
    public string $directory = 'attachments';
    public string $accept = '.pdf,.jpg,.jpeg,.png';
    public int $maxSizeKB = 10240;
    public bool $multiple = true;
    public string $label = 'Adjuntos';
    public ?string $help = 'Arrastra y suelta, o haz clic para seleccionar (PDF/JPG/PNG, máx. 10MB c/u).';

    public function mount($files = null): void
    {
        // Normaliza a arreglo
        $this->files = is_array($files) ? $files : [];
    }

    protected function rules(): array
    {
        return ['uploads.*' => "mimes:pdf,jpg,jpeg,png|max:{$this->maxSizeKB}"];
    }

    public function updatedUploads(): void
    {
        $this->validate();

        foreach ($this->uploads as $file) {
            $path = $file->store($this->directory, $this->disk);

            $this->files[] = [
                'path' => ($this->disk === 'public' ? 'storage/' : '') . $path,
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ];
        }

        $this->uploads = [];
    }

    public function remove(int $i): void
    {
        if (!isset($this->files[$i])) return;

        $rel = $this->files[$i]['path'] ?? '';
        if ($this->disk === 'public' && str_starts_with($rel, 'storage/')) {
            $rel = substr($rel, 8); // quita 'storage/'
        }
        if ($rel && Storage::disk($this->disk)->exists($rel)) {
            Storage::disk($this->disk)->delete($rel);
        }

        unset($this->files[$i]);
        $this->files = array_values($this->files);
    }

    public function render()
    {
        return view('livewire.ui.dropzone');
    }
}
