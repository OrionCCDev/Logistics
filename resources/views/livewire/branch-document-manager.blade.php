<div class="p-6 bg-white rounded-lg shadow-lg">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">Branch Documents</h2>

        <!-- Upload Form -->
        <form wire:submit="uploadDocument" class="mb-6">
            <div class="flex gap-4">
                <input type="file" wire:model="document" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Upload
                </button>
            </div>
            @error('document') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </form>

        <!-- QR Code Section -->
        <div class="mb-6">
            <button wire:click="generateQrCode" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Generate QR Code
            </button>

            @if($showQrCode)
                <div class="mt-4 p-4 border rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Branch QR Code</h3>
                    <div class="flex justify-center">
                        {!! $qrCode !!}
                    </div>
                </div>
            @endif
        </div>

        <!-- Download Branch Details -->
        <div class="mb-6">
            <button wire:click="downloadBranchDetails" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Download Branch Details PDF
            </button>
        </div>

        <!-- Documents List -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Uploaded Documents</h3>
            <div class="space-y-4">
                @forelse($documents as $document)
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <div>
                            <span class="font-medium">{{ $document->file_name }}</span>
                            <span class="text-sm text-gray-500 ml-2">
                                ({{ number_format($document->size / 1024, 2) }} KB)
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="downloadPdf({{ $document->id }})"
                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Download
                            </button>
                            <button wire:click="deleteDocument({{ $document->id }})"
                                    wire:confirm="Are you sure you want to delete this document?"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No documents uploaded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
