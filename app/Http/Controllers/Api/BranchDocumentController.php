<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BranchDocumentController extends Controller
{
    public function getDocuments(Branch $branch)
    {
        return response()->json([
            'documents' => $branch->getPdfFiles()->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'size' => $media->size,
                    'url' => $media->getUrl(),
                    'created_at' => $media->created_at,
                ];
            })
        ]);
    }

    public function getQrCode(Branch $branch)
    {
        return response()->json([
            'qr_code' => $branch->generateQrCode()
        ]);
    }

    public function downloadDocument(Branch $branch, Media $media)
    {
        if ($media->model_id !== $branch->id) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        return response()->download($media->getPath(), $media->file_name);
    }

    public function downloadBranchDetails(Branch $branch)
    {
        return $branch->generatePdf('pdf.branch-details', [], 'branch-details.pdf');
    }

    public function uploadDocument(Request $request, Branch $branch)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:10240'
        ]);

        $media = $branch->uploadPdf($request->file('document'));

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => [
                'id' => $media->id,
                'name' => $media->file_name,
                'size' => $media->size,
                'url' => $media->getUrl(),
                'created_at' => $media->created_at,
            ]
        ]);
    }

    public function deleteDocument(Branch $branch, Media $media)
    {
        if ($media->model_id !== $branch->id) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        $branch->deletePdfFile($media);

        return response()->json([
            'message' => 'Document deleted successfully'
        ]);
    }
}
