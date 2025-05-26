<?php

namespace App\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

trait HasPdfAndQrCode
{
    /**
     * Upload a PDF file to the model
     */
    public function uploadPdf($file, string $collectionName = 'documents'): Media
    {
        return $this->addMedia($file)
            ->usingName($file->getClientOriginalName())
            ->usingFileName($file->getClientOriginalName())
            ->toMediaCollection($collectionName);
    }

    /**
     * Generate a QR code for the model
     */
    public function generateQrCode(string $data = null, int $size = 300): string
    {
        $data = $data ?? route('api.' . strtolower(class_basename($this)) . '.show', $this->id);
        return QrCode::size($size)->generate($data);
    }

    /**
     * Generate a PDF from a view
     */
    public function generatePdf(string $view, array $data = [], string $filename = null): \Barryvdh\DomPDF\PDF
    {
        $data = array_merge(['model' => $this], $data);
        $pdf = PDF::loadView($view, $data);

        if ($filename) {
            $pdf->setPaper('a4')->setWarnings(false);
            return $pdf->download($filename);
        }

        return $pdf;
    }

    /**
     * Get all PDF files from a specific collection
     */
    public function getPdfFiles(string $collectionName = 'documents'): \Illuminate\Support\Collection
    {
        return $this->getMedia($collectionName);
    }

    /**
     * Delete a specific PDF file
     */
    public function deletePdfFile(Media $media): bool
    {
        return $media->delete();
    }
}
