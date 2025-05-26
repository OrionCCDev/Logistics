@extends('layouts.app')

@section('page_title', 'Edit Invoice')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .select2-container .select2-selection--single {
        height: calc(1.5em + .75rem + 2px);
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: calc(1.5em + .75rem + 2px);
        padding-left: .75rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + .75rem + 2px);
    }
    .item-row:not(:first-child) .remove-item-btn,
    .item-row:first-child:not(:only-child) .remove-item-btn {
        display: block !important; /* Show remove if it's not the only row */
    }
    .current-attachments .btn-remove-attachment {
        font-size: 0.8em;
        padding: 0.2em 0.5em;
    }
</style>
@endpush

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('invoices.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Invoices
    </a>
</div>
<a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-eye"></i></span>
    <span class="btn-text">View Invoice</span>
</a>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! Something went wrong.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-sm">
                    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" enctype="multipart/form-data" id="editInvoiceForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Supplier -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="supplier_id">Supplier</label>
                                    <select class="form-control select2 @error('supplier_id') is-invalid @enderror" name="supplier_id" id="supplier_id">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier_item)
                                            <option value="{{ $supplier_item->id }}" {{ old('supplier_id', $invoice->supplier_id) == $supplier_item->id ? 'selected' : '' }}>{{ $supplier_item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Invoice Number -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="invoice_number">Invoice Number</label>
                                    <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" placeholder="Enter invoice number">
                                    @error('invoice_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <!-- Submission Date -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="submission_date">Submission Date</label>
                                    <input type="text" class="form-control flatpickr @error('submission_date') is-invalid @enderror" name="submission_date" id="submission_date" value="{{ old('submission_date', $invoice->submission_date ? $invoice->submission_date->format('d/m/Y') : '') }}" placeholder="Select submission date">
                                    @error('submission_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Invoice From Date -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="invoice_from_date">Invoice From Date</label>
                                    <input type="text" class="form-control flatpickr @error('invoice_from_date') is-invalid @enderror" name="invoice_from_date" id="invoice_from_date" value="{{ old('invoice_from_date', $invoice->invoice_from_date ? $invoice->invoice_from_date->format('d/m/Y') : '') }}" placeholder="Select invoice from date">
                                    @error('invoice_from_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Invoice To Date -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="invoice_to_date">Invoice To Date</label>
                                    <input type="text" class="form-control flatpickr @error('invoice_to_date') is-invalid @enderror" name="invoice_to_date" id="invoice_to_date" value="{{ old('invoice_to_date', $invoice->invoice_to_date ? $invoice->invoice_to_date->format('d/m/Y') : '') }}" placeholder="Select invoice to date">
                                    @error('invoice_to_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-6">
                                <!-- Currency -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="currency_code">Currency</label>
                                    <select class="form-control @error('currency_code') is-invalid @enderror" name="currency_code" id="currency_code">
                                        <option value="AED" {{ old('currency_code', $invoice->currency_code) == 'AED' ? 'selected' : '' }}>AED</option>
                                        <option value="USD" {{ old('currency_code', $invoice->currency_code) == 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="EUR" {{ old('currency_code', $invoice->currency_code) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        <option value="GBP" {{ old('currency_code', $invoice->currency_code) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                        <!-- Add more currencies as needed -->
                                    </select>
                                    @error('currency_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- PO Number -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="po_number">PO Number (Optional)</label>
                                    <input type="text" class="form-control @error('po_number') is-invalid @enderror" name="po_number" id="po_number" value="{{ old('po_number', $invoice->po_number) }}" placeholder="Enter PO number">
                                    @error('po_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <h5 class="mt-4 mb-3">Invoice Items</h5>
                        <div id="invoice-items-container">
                            <!-- Item Row Template (hidden) -->
                            <div class="row item-row mb-2 d-none" id="item-row-template">
                                <input type="hidden" class="item-id" name="items[__INDEX__][id]" value="" disabled>
                                <input type="hidden" class="item-vehicle-id" name="items[__INDEX__][vehicle_id]" value="" disabled>
                                <div class="col-md-4">
                                    <input type="text" class="form-control-plaintext item-vehicle-name bg-light" readonly placeholder="Vehicle" disabled>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control item-working-hours" name="items[__INDEX__][working_hours]" placeholder="Hours" min="0.01" step="0.01" disabled>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control item-unit-price" name="items[__INDEX__][unit_price]" placeholder="Unit Price" step="0.01" min="0" disabled>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control item-total bg-light" name="items[__INDEX__][total]" placeholder="Total" readonly disabled>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn" style="display:none;" disabled><i class="fa fa-trash"></i> Remove</button>
                                </div>
                            </div>

                            <!-- Initial Item Row (Labels for header) -->
                            <div class="row item-row-header mb-2 font-weight-bold">
                                <div class="col-md-4"><label>Vehicle</label></div>
                                <div class="col-md-2"><label>Working Hours</label></div>
                                <div class="col-md-2"><label>Unit Price</label></div>
                                <div class="col-md-2"><label>Total</label></div>
                                <div class="col-md-2"><label>&nbsp;</label></div>
                            </div>

                            @php
                                $currentInvoiceItems = $invoice->items->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'vehicle_id' => $item->vehicle_id,
                                        'vehicle_name' => $item->vehicle ? ($item->vehicle->plate_number ?: 'N/A') . ' (' . ($item->vehicle->vehicle_model ?: 'N/A') . ')' : 'Vehicle not found',
                                        'working_hours' => $item->working_hours,
                                        'unit_price' => $item->unit_price,
                                        'total' => $item->total
                                    ];
                                })->toArray();
                                $itemsToRender = old('items', $currentInvoiceItems);
                            @endphp

                            @if(!empty($itemsToRender))
                                @foreach($itemsToRender as $index => $item)
                                <div class="row item-row mb-2">
                                    <input type="hidden" class="item-id" name="items[{{ $index }}][id]" value="{{ $item['id'] ?? '' }}">
                                    <input type="hidden" class="item-vehicle-id" name="items[{{ $index }}][vehicle_id]" value="{{ $item['vehicle_id'] ?? '' }}">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control-plaintext item-vehicle-name bg-light @error("items.{$index}.vehicle_id") is-invalid @enderror" readonly value="{{ $item['vehicle_name'] ?? ($item['vehicle_id'] ? 'Loading vehicle...' : 'N/A') }}">
                                        @error("items.{$index}.vehicle_id") <div class="invalid-feedback d-block">{{ $message }} (Item Index: {{ $index }})</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control item-working-hours @error("items.{$index}.working_hours") is-invalid @enderror" name="items[{{ $index }}][working_hours]" placeholder="Hours" min="0.01" step="0.01" value="{{ $item['working_hours'] ?? '' }}">
                                        @error("items.{$index}.working_hours") <div class="invalid-feedback d-block">{{ $message }} (Item Index: {{ $index }})</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control item-unit-price @error("items.{$index}.unit_price") is-invalid @enderror" name="items[{{ $index }}][unit_price]" placeholder="Unit Price" step="0.01" min="0" value="{{ $item['unit_price'] ?? '' }}">
                                        @error("items.{$index}.unit_price") <div class="invalid-feedback d-block">{{ $message }} (Item Index: {{ $index }})</div> @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control item-total bg-light" name="items[{{ $index }}][total]" placeholder="Total" readonly value="{{ $item['total'] ?? (($item['working_hours'] ?? 0) * ($item['unit_price'] ?? 0)) }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-item-btn" @if(count($itemsToRender) <= 1 && !old('items')) style="display:none;" @else style="display:block;" @endif><i class="fa fa-trash"></i> Remove</button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                 <div class="row item-row-placeholder text-muted text-center p-3">
                                    <div class="col">No items added yet. Click "Add Vehicle Item" to begin.</div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="open-add-vehicle-item-modal-btn" {{ old('supplier_id', $invoice->supplier_id) ? '' : 'disabled' }}><i class="fa fa-truck"></i> Add Vehicle Item</button>

                        <!-- Totals -->
                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-6">
                                <div class="form-group row">
                                    <label for="subtotal" class="col-sm-4 col-form-label text-right">Subtotal</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext text-right fw-bold" id="subtotal" value="{{ old('subtotal_amount', number_format($invoice->subtotal, 2, '.', '')) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax_rate" class="col-sm-4 col-form-label text-right">Tax Rate (%)</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control @error('tax_rate') is-invalid @enderror" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $invoice->tax_rate) }}" placeholder="Enter tax rate" step="0.01" min="0">
                                        @error('tax_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax_amount" class="col-sm-4 col-form-label text-right">Tax Amount</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext text-right fw-bold" id="tax_amount" value="{{ old('final_tax_amount', number_format($invoice->tax_amount, 2, '.', '')) }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="grand_total" class="col-sm-4 col-form-label text-right h4">Grand Total</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext text-right fw-bold h4" id="grand_total" name="amount" value="{{ old('amount', number_format($invoice->total_amount, 2, '.', '')) }}">
                                        <input type="hidden" name="subtotal_amount" id="subtotal_amount_hidden" value="{{ old('subtotal_amount', $invoice->subtotal) }}">
                                        <input type="hidden" name="final_tax_amount" id="final_tax_amount_hidden" value="{{ old('final_tax_amount', $invoice->tax_amount) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group mt-3">
                            <label class="control-label mb-10" for="notes">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" rows="3" placeholder="Enter any notes for the invoice">{{ old('notes', $invoice->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Invoice File -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="invoice_file">Invoice Document (Optional)</label>
                            @if($invoice->invoice_file_path)
                            <div class="mb-2">
                                Current file: <a href="{{ asset($invoice->invoice_file_path) }}" target="_blank">{{ basename($invoice->invoice_file_path) }}</a>
                            </div>
                            @endif
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                <div class="form-control text-truncate @error('invoice_file') is-invalid @enderror" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-append">
                                    <span class="btn btn-primary btn-file">
                                        <span class="fileinput-new">Select file</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="invoice_file" id="invoice_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    </span>
                                    <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </span>
                            </div>
                            @error('invoice_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Upload a new invoice document (PDF, DOC, DOCX, JPG, PNG). If you upload a new file, the existing one will be replaced.</small>
                        </div>


                        <!-- Status -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="status">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                <option value="pending" {{ old('status', $invoice->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-wth-icon">
                                <span class="icon-label"><i class="fa fa-save"></i></span>
                                <span class="btn-text">Update Invoice</span>
                            </button>
                            <a href="{{ route('invoices.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    console.log("[DEBUG] Initial Script Load");
    console.log("[DEBUG] Initial itemIndex (from PHP calc):", {{ count(old('items', $currentInvoiceItems)) }});
    console.log("[DEBUG] Old items data (JSON):", @json(old('items')));
    console.log("[DEBUG] Current Invoice items data (from DB pre-map, JSON):", @json($invoice->items->toArray()));
    console.log("[DEBUG] Items to Render (after old() check, JSON):", @json($itemsToRender));

    $(document).ready(function() {
        console.log("[DEBUG] Document ready started.");

        $('.select2').select2({
            placeholder: "Select Supplier",
            allowClear: true
        });

        flatpickr(".flatpickr", {
            dateFormat: "d/m/Y",
            altInput: true,
            altFormat: "d/m/Y",
            allowInput: true, // Explicitly allow manual input
        });

        let itemIndex = {{ count(old('items', $currentInvoiceItems)) }};
        console.log("[DEBUG] JS itemIndex initialized to:", itemIndex);
        console.log("[DEBUG] Initial rendered item rows count (in DOM):", $('#invoice-items-container .item-row:not(.item-row-header):not(.item-row-placeholder)').length);

        // Enable/disable "Add Vehicle Item" button based on supplier selection
        $('#supplier_id').on('change', function() {
            console.log("[DEBUG] Supplier changed.");
            if ($(this).val()) {
                $('#open-add-vehicle-item-modal-btn').prop('disabled', false);
            } else {
                $('#open-add-vehicle-item-modal-btn').prop('disabled', true);
            }
        });


        function updateRowIndices() {
            console.log("[DEBUG] updateRowIndices CALLED. Current itemIndex before update:", itemIndex);
            let visibleRows = 0;
            $('#invoice-items-container .item-row:not(.item-row-header):not(.item-row-placeholder)').each(function(idx) {
                const $row = $(this);
                console.log("[DEBUG] updateRowIndices - Processing row:", $row.find('.item-vehicle-name').val() || 'Empty Vehicle Name', "Current name:", $row.find('.item-vehicle-id').attr('name'));
                $row.find('input, select, textarea').each(function() {
                    if (this.name && this.name.includes('items[')) {
                        const oldName = this.name;
                        this.name = this.name.replace(/items\[(\d+|__INDEX__)\]/, `items[${visibleRows}]`);
                        if (oldName !== this.name) {
                           // console.log(`[DEBUG] updateRowIndices - Name changed from ${oldName} to ${this.name}`);
                        }
                    }
                });
                visibleRows++;
            });

            const totalVisibleRows = $('#invoice-items-container .item-row:not(.item-row-header):not(.item-row-placeholder)').length;
            console.log("[DEBUG] updateRowIndices - Total visible rows after processing names:", totalVisibleRows);
            $('#invoice-items-container .item-row:not(.item-row-header):not(.item-row-placeholder)').each(function(idx) {
                if (totalVisibleRows > 1) {
                    $(this).find('.remove-item-btn').show();
                } else {
                    $(this).find('.remove-item-btn').hide();
                }
            });
             if (totalVisibleRows === 0 && !$('#invoice-items-container .item-row-placeholder').length) {
                console.log("[DEBUG] updateRowIndices - No visible items, adding placeholder.");
                $('#invoice-items-container').append('<div class="row item-row-placeholder text-muted text-center p-3"><div class="col">No items added yet. Click "Add Vehicle Item" to begin.</div></div>');
            } else if (totalVisibleRows > 0 && $('#invoice-items-container .item-row-placeholder').length) {
                console.log("[DEBUG] updateRowIndices - Visible items found, removing placeholder.");
                $('#invoice-items-container .item-row-placeholder').remove();
            }
            console.log("[DEBUG] updateRowIndices FINISHED. Visible rows counted:", visibleRows, "New itemIndex to be set globally from this count:", visibleRows);
            return visibleRows; // This will be used to set the global itemIndex if called like itemIndex = updateRowIndices()
        }


        function calculateItemTotal(row) {
            // console.log("[DEBUG] calculateItemTotal CALLED for row.");
            const quantity = parseFloat(row.find('.item-working-hours').val()) || 0;
            const unitPrice = parseFloat(row.find('.item-unit-price').val()) || 0;
            const total = quantity * unitPrice;
            row.find('.item-total').val(total.toFixed(2));
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            // console.log("[DEBUG] calculateGrandTotal CALLED.");
            let subtotal = 0;
            $('#invoice-items-container .item-row:not(.item-row-header):not(.item-row-placeholder)').each(function() {
                const itemTotal = parseFloat($(this).find('.item-total').val()) || 0;
                subtotal += itemTotal;
            });
            $('#subtotal').val(subtotal.toFixed(2));
            $('#subtotal_amount_hidden').val(subtotal.toFixed(2));

            const taxRate = parseFloat($('#tax_rate').val()) || 0;
            const taxAmount = (subtotal * taxRate) / 100;
            $('#tax_amount').val(taxAmount.toFixed(2));
            $('#final_tax_amount_hidden').val(taxAmount.toFixed(2));

            const grandTotal = subtotal + taxAmount;
            $('#grand_total').val(grandTotal.toFixed(2));
        }

        window.addVehicleItemToForm = function(vehicleData) {
            console.log("[DEBUG] addVehicleItemToForm CALLED. Data:", JSON.stringify(vehicleData), "Current global itemIndex before adding:", itemIndex);

            const vehicleId = vehicleData.id;
            const vehicleName = vehicleData.name || 'N/A';
            const workingHours = vehicleData.working_hours || 1;
            const unitPrice = (typeof vehicleData.unit_price !== 'undefined' && vehicleData.unit_price !== null) ? vehicleData.unit_price : '0';

            if (typeof vehicleId === 'undefined' || vehicleId === null || String(vehicleId).trim() === '') {
                console.error("[DEBUG] addVehicleItemToForm - Vehicle ID is missing or empty. Cannot add item. Provided ID:", vehicleId);
                alert('Error: Vehicle ID is missing. Item not added.');
                return;
            }

            $('.item-row-placeholder').remove();

            const newItemRow = $('#item-row-template').clone().removeAttr('id').removeClass('d-none');
            console.log("[DEBUG] addVehicleItemToForm - Template cloned.");

            newItemRow.find('input, select, textarea, button').each(function() {
                $(this).prop('disabled', false);
                if (this.name && this.name.includes('items[')) {
                   this.name = this.name.replace(/__INDEX__/, `${itemIndex}`);
                }
            });

            newItemRow.find('.item-vehicle-id').val(vehicleId);
            newItemRow.find('.item-vehicle-name').val(vehicleName);
            newItemRow.find('.item-working-hours').val(workingHours);
            newItemRow.find('.item-unit-price').val(unitPrice);

            $('#invoice-items-container').append(newItemRow);
            console.log("[DEBUG] addVehicleItemToForm - New item row appended to container.");

            calculateItemTotal(newItemRow);
            itemIndex = updateRowIndices(); // Update global itemIndex to the new count of rows
            console.log("[DEBUG] addVehicleItemToForm - Global itemIndex updated to:", itemIndex, "after updateRowIndices call.");
            calculateGrandTotal(); // Recalculate grand total after adding new item and its total
        }

        $('#open-add-vehicle-item-modal-btn').on('click', function() {
            console.log("[DEBUG] Add Vehicle Item button clicked. Current global itemIndex:", itemIndex);
            // This is where you would open your modal for selecting vehicles
            alert("Modal for adding vehicle items needs to be implemented. Refer to create page's modal functionality. This button does NOT currently add an item row.");
            // For testing addVehicleItemToForm, you can manually call it from the console with sample data, e.g.:
            // window.addVehicleItemToForm({id: new Date().getTime(), name: 'Test Vehicle', working_hours: 5, unit_price: 10});
        });


        $('#invoice-items-container').on('click', '.remove-item-btn', function() {
            console.log("[DEBUG] Remove item button clicked. Current global itemIndex before removal:", itemIndex);
            $(this).closest('.item-row').remove();
            itemIndex = updateRowIndices(); // Update global itemIndex after removal
            console.log("[DEBUG] Item removed. Global itemIndex updated to:", itemIndex, "after updateRowIndices call.");
            calculateGrandTotal();
        });

        $('#invoice-items-container').on('input', '.item-working-hours, .item-unit-price', function() {
            calculateItemTotal($(this).closest('.item-row'));
        });

        $('#tax_rate').on('input', function() {
            calculateGrandTotal();
        });

        // Initial calculation and setup
        console.log("[DEBUG] Initializing totals and indices for existing rows...");
        $('#invoice-items-container .item-row:not(.item-row-header):not(.item-row-placeholder)').each(function(){
            calculateItemTotal($(this));
        });
        itemIndex = updateRowIndices(); // Set correct itemIndex based on initially rendered rows
        console.log("[DEBUG] Initial setup complete. Global itemIndex is now:", itemIndex);
        calculateGrandTotal();

        // Log form data on submit for debugging
        // $('#editInvoiceForm').on('submit', function(e) {
        //     var formData = $(this).serializeArray();
        //     console.log("[DEBUG] Form data on submit:", formData);
        //     // To see if an empty item is being submitted:
        //     formData.forEach(function(field) {
        //         if (field.name.startsWith('items[') && field.name.endsWith('[vehicle_id]') && field.value === '') {
        //             console.warn("[DEBUG] SUBMITTING EMPTY VEHICLE ID:", field.name);
        //         }
        //     });
        //     // e.preventDefault(); // Uncomment to stop submission for debugging
        // });

    });
</script>
@endpush
