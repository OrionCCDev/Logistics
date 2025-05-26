<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Supplier;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['supplier', 'vehicles'])->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        // $vehicles = Vehicle::all(); // We will fetch vehicles via AJAX
        return view('invoices.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number',
            'submission_date' => 'required|date',
            'invoice_from_date' => 'required|date',
            'invoice_to_date' => 'required|date|after_or_equal:invoice_from_date',
            'status' => 'required|string|in:pending,paid,overdue,cancelled',
            'po_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'items' => 'required|array|min:1',
            'items.*.vehicle_id' => 'required|integer|exists:vehicles,id',
            'items.*.working_hours' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'subtotal_amount' => 'required|numeric|min:0',
            'final_tax_amount' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $invoiceFilePath = null;
        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $uploadPath = public_path('uploads/invoices/attachments');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }
            $file->move($uploadPath, $filename);
            $invoiceFilePath = 'uploads/invoices/attachments/' . $filename;
        }

        $invoiceData = [
            'supplier_id' => $data['supplier_id'],
            'invoice_number' => $data['invoice_number'],
            'submission_date' => $data['submission_date'],
            'invoice_from_date' => $data['invoice_from_date'],
            'invoice_to_date' => $data['invoice_to_date'],
            'status' => $data['status'],
            'po_number' => $data['po_number'] ?? null,
            'notes' => $data['notes'] ?? null,
            'subtotal' => $data['subtotal_amount'],
            'tax_rate' => $data['tax_rate'] ?? 0,
            'tax_amount' => $data['final_tax_amount'],
            'total_amount' => $data['amount'],
            'invoice_file_path' => $invoiceFilePath,
        ];

        $invoice = Invoice::create($invoiceData);

        // $vehicleIds = []; // Replaced with logic to store aggregated data
        $vehiclePivotData = [];
        $taxRateValue = $data['tax_rate'] ?? 0; // Use the invoice's tax rate, default to 0 if not provided

        foreach ($data['items'] as $itemData) {
            $invoice->items()->create([
                'vehicle_id' => $itemData['vehicle_id'],
                'working_hours' => $itemData['working_hours'],
                'unit_price' => $itemData['unit_price'],
                'total' => $itemData['working_hours'] * $itemData['unit_price'],
            ]);

            $vehicleId = $itemData['vehicle_id'];
            $hours = $itemData['working_hours'];
            $costWithoutTax = $hours * $itemData['unit_price'];

            if (!isset($vehiclePivotData[$vehicleId])) {
                $vehiclePivotData[$vehicleId] = [
                    'total_hours' => 0,
                    'total_cost_without_tax' => 0,
                ];
            }
            $vehiclePivotData[$vehicleId]['total_hours'] += $hours;
            $vehiclePivotData[$vehicleId]['total_cost_without_tax'] += $costWithoutTax;
        }

        // Prepare data for sync, including tax calculation
        $syncData = [];
        foreach ($vehiclePivotData as $vehicleId => $totals) {
            $costWithTax = $totals['total_cost_without_tax'] * (1 + ($taxRateValue / 100));
            $syncData[$vehicleId] = [
                'total_hours' => $totals['total_hours'],
                'total_cost_without_tax' => $totals['total_cost_without_tax'],
                'total_cost_with_tax' => $costWithTax,
            ];
        }

        // Sync vehicles to the invoice_vehicle pivot table with additional data
        $invoice->vehicles()->sync($syncData);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['supplier', 'vehicles']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $suppliers = Supplier::all();
        $invoice->load(['items.vehicle', 'supplier']); // Removed 'attachments' eager loading
        return view('invoices.edit', compact('invoice', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Convert date formats before validation
        $this->transformDate($request, 'submission_date');
        $this->transformDate($request, 'invoice_from_date');
        $this->transformDate($request, 'invoice_to_date');

        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $invoice->id,
            'submission_date' => 'required|date',
            'invoice_from_date' => 'required|date',
            'invoice_to_date' => 'required|date|after_or_equal:invoice_from_date',
            'status' => 'required|string|in:pending,paid,overdue,cancelled',
            'currency_code' => 'required|string|max:10',
            'po_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', // Single invoice file
            'remove_invoice_file' => 'nullable|boolean',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|integer|exists:invoice_items,id,invoice_id,' . $invoice->id,
            'items.*.vehicle_id' => 'required|integer|exists:vehicles,id',
            'items.*.working_hours' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'subtotal_amount' => 'required|numeric|min:0',
            'final_tax_amount' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        DB::beginTransaction();
        try {
            $finalPathForDB = $invoice->invoice_file_path; // Start with the current path in DB

            // 1. Handle new file upload if one is present
            if ($request->hasFile('invoice_file')) {
                $file = $request->file('invoice_file');
                $newFileName = 'invoice_' . $invoice->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                $uploadDirectory = 'uploads/invoices/attachments'; // Relative to public_path()
                $fullUploadPathDir = public_path($uploadDirectory);

                // Ensure the target directory exists
                if (!File::isDirectory($fullUploadPathDir)) {
                    File::makeDirectory($fullUploadPathDir, 0755, true, true);
                }

                try {
                    $file->move($fullUploadPathDir, $newFileName); // Move to public/uploads/invoices/attachments/
                    $newDbPath = $uploadDirectory . '/' . $newFileName; // Path for DB: "uploads/invoices/attachments/file.ext"

                    // New file uploaded successfully.
                    // If there was an old file path (held by $finalPathForDB before this block), delete it from its public location.
                    if ($finalPathForDB && File::exists(public_path($finalPathForDB))) {
                        File::delete(public_path($finalPathForDB));
                    }
                    $finalPathForDB = $newDbPath; // Update path to be stored in DB to the new one.

                } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $e) {
                    DB::rollBack();
                    // Log::error('Invoice file move failed during update: ' . $e->getMessage()); // Optional: Log error
                    return redirect()->back()->with('error', 'Failed to upload the new invoice file: ' . $e->getMessage())->withInput();
                }
            }

            $invoiceData = [
                'supplier_id' => $data['supplier_id'],
                'invoice_number' => $data['invoice_number'],
                'submission_date' => $data['submission_date'],
                'invoice_from_date' => $data['invoice_from_date'],
                'invoice_to_date' => $data['invoice_to_date'],
                'currency_code' => $data['currency_code'],
                'status' => $data['status'],
                'po_number' => $data['po_number'] ?? null,
                'notes' => $data['notes'] ?? null,
                'subtotal' => $data['subtotal_amount'],
                'tax_rate' => $data['tax_rate'] ?? 0,
                'tax_amount' => $data['final_tax_amount'],
                'total_amount' => $data['amount'],
                'invoice_file_path' => $finalPathForDB, // Use the determined final path
            ];

            $invoice->update($invoiceData);

            // Update Invoice Items (Vehicle based)
            $existingItemIds = $invoice->items()->pluck('id')->toArray();
            $submittedItemIds = [];
            $vehiclePivotData = [];
            $taxRateValue = $data['tax_rate'] ?? 0;

            foreach ($data['items'] as $itemData) {
                $itemDetails = [
                    'vehicle_id' => $itemData['vehicle_id'],
                    'working_hours' => $itemData['working_hours'],
                    'unit_price' => $itemData['unit_price'],
                    'total' => $itemData['working_hours'] * $itemData['unit_price'],
                ];

                if (!empty($itemData['id'])) { // Existing item
                    $item = $invoice->items()->find($itemData['id']);
                    if ($item) {
                        $item->update($itemDetails);
                        $submittedItemIds[] = $item->id;
                    }
                } else { // New item
                    $newItem = $invoice->items()->create($itemDetails);
                    $submittedItemIds[] = $newItem->id;
                }

                $vehicleId = $itemData['vehicle_id'];
                $hours = $itemData['working_hours'];
                $costWithoutTax = $hours * $itemData['unit_price'];

                if (!isset($vehiclePivotData[$vehicleId])) {
                    $vehiclePivotData[$vehicleId] = [
                        'total_hours' => 0,
                        'total_cost_without_tax' => 0,
                    ];
                }
                $vehiclePivotData[$vehicleId]['total_hours'] += $hours;
                $vehiclePivotData[$vehicleId]['total_cost_without_tax'] += $costWithoutTax;
            }

            $idsToDelete = array_diff($existingItemIds, $submittedItemIds);
            if (!empty($idsToDelete)) {
                $invoice->items()->whereIn('id', $idsToDelete)->delete();
            }

            $syncData = [];
            foreach ($vehiclePivotData as $vehicleId => $totals) {
                $costWithTax = $totals['total_cost_without_tax'] * (1 + ($taxRateValue / 100));
                $syncData[$vehicleId] = [
                    'total_hours' => $totals['total_hours'],
                    'total_cost_without_tax' => $totals['total_cost_without_tax'],
                    'total_cost_with_tax' => $costWithTax,
                ];
            }

            $invoice->vehicles()->sync($syncData);

            DB::commit();
            return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Invoice update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating invoice: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper function to transform date format.
     */
    private function transformDate(Request $request, string $field)
    {
        if ($request->has($field) && $request->input($field)) {
            try {
                $request->merge([$field => Carbon::createFromFormat('d/m/Y', $request->input($field))->format('Y-m-d')]);
            } catch (\Exception $e) {
                // If parsing fails, let the validator handle the original (likely invalid) format
                // Or, you could add a custom error here if preferred.
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->invoice_file_path && File::exists(public_path($invoice->invoice_file_path))) {
            File::delete(public_path($invoice->invoice_file_path));
        }

        $invoice->vehicles()->detach();
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Get vehicles for a specific supplier.
     */
    public function getSupplierVehicles(Request $request, Supplier $supplier)
    {
        // Assuming you have a relationship set up in your Supplier model:
        // public function vehicles() {
        //     return $this->belongsToMany(Vehicle::class, 'supplier_vehicle_pivot_table_name');
        // }
        // Or if vehicles are directly related to a supplier_id on the vehicles table
        // $vehicles = Vehicle::where('supplier_id', $supplier->id)->get(['id', 'name']); // Adjust 'name' to your vehicle display attribute

        // For demonstration, let's assume a direct relationship or a simple query
        // You'll need to adjust this query based on your actual database schema and relationships
        // For example, if vehicles are linked to suppliers through a pivot table or a direct foreign key.

        // If you have a vehicles relationship defined in your Supplier model:
        // $vehicles = $supplier->vehicles()->select('id', 'plate_number as text')->get(); // Assuming 'plate_number' is what you want to display

        // If not, and you have a 'supplier_id' on the 'vehicles' table:
        // $vehicles = Vehicle::where('supplier_id', $supplier->id)->select('id', 'plate_number as text')->get();

        // Placeholder: Replace with your actual logic to get supplier-specific vehicles
        // For now, let's simulate fetching all vehicles for demonstration if a supplier is chosen,
        // or an empty list. You MUST adapt this part to your actual data model.
        if ($supplier) {
            // This is a placeholder. You should fetch vehicles associated with the $supplier.
            // For example, if your Vehicle model has a supplier_id:
            // $vehicles = Vehicle::where('supplier_id', $supplier->id)
            //                    ->select('id', \DB::raw('CONCAT(plate_number, " (", make, " ", model, ")") as text'))
            //                    ->get();
            // Or if you have a pivot table, use the relationship:
            // $vehicles = $supplier->vehicles()
            //                    ->select('vehicles.id', \DB::raw('CONCAT(vehicles.plate_number, " (", vehicles.make, " ", vehicles.model, ")") as text'))
            //                    ->get();

            // Using a generic approach for now. Please replace with your actual vehicle fetching logic.
            // This example assumes your Vehicle model has 'id' and 'plate_number'
            // and you want to display 'plate_number' in the select2.
            $searchTerm = $request->input('q');
            $query = $supplier->vehicles(); // Assuming a 'vehicles' relationship exists on the Supplier model

            if ($searchTerm) {
                // Adjust the fields you want to search in
                $query->where(function($q) use ($searchTerm) {
                    $q->where('plate_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('vehicle_type', 'like', '%' . $searchTerm . '%')
                      ->orWhere('vehicle_model', 'like', '%' . $searchTerm . '%');
                });
            }

            $vehicles = $query->select('vehicles.id', DB::raw('CONCAT(vehicles.plate_number, " (", vehicles.vehicle_model, ")") as text'))->get();


        } else {
            $vehicles = collect();
        }

        return response()->json(['results' => $vehicles]);
    }
}
