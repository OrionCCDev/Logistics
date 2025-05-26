<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Branch;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BranchDocumentManager extends Component
{
    use WithFileUploads;

    public Branch $branch;
    public $document;
    public $qrCode;
    public $showQrCode = false;
    public $selectedDocument;

    protected $rules = [
        'document' => 'required|file|mimes:pdf|max:10240', // 10MB max
    ];

    public function mount(Branch $branch)
    {
        $this->branch = $branch;
    }

    public function uploadDocument()
    {
        $this->validate();

        $this->branch->uploadPdf($this->document);
        $this->document = null;
        $this->dispatch('document-uploaded');
    }

    public function generateQrCode()
    {
        $this->qrCode = $this->branch->generateQrCode();
        $this->showQrCode = true;
    }

    public function downloadPdf(Media $media)
    {
        return response()->download($media->getPath(), $media->file_name);
    }

    public function deleteDocument(Media $media)
    {
        $this->branch->deletePdfFile($media);
        $this->dispatch('document-deleted');
    }

    public function downloadBranchDetails()
    {
        return $this->branch->generatePdf('pdf.branch-details', [], 'branch-details.pdf');
    }

    public function render()
    {
        return view('livewire.branch-document-manager', [
            'documents' => $this->branch->getPdfFiles()
        ]);
    }
}
