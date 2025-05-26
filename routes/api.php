<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BranchDocumentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Branch Document Routes
Route::prefix('branches/{branch}')->group(function () {
    Route::get('/documents', [BranchDocumentController::class, 'getDocuments']);
    Route::get('/qr-code', [BranchDocumentController::class, 'getQrCode']);
    Route::get('/documents/{media}', [BranchDocumentController::class, 'downloadDocument']);
    Route::get('/details-pdf', [BranchDocumentController::class, 'downloadBranchDetails']);
    Route::post('/documents', [BranchDocumentController::class, 'uploadDocument']);
    Route::delete('/documents/{media}', [BranchDocumentController::class, 'deleteDocument']);
});
