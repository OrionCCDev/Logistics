@extends('layouts.app')

@section('page_title', 'Create Invoice')

@section('head_styles')
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
    .item-row:not(:first-child) .remove-item-btn {
        display: block !important;
    }
</style>
@endsection

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('invoices.index') }}" type="button" class="btn btn-outline-primary">Invoices</a>
</div>
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
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-sm">
                    <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data" id="createInvoiceForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Supplier -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="supplier_id">Supplier</label>
                                    <select class="form-control select2 @error('supplier_id') is-invalid @enderror" name="supplier_id" id="supplier_id">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
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
                                    <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" name="invoice_number" id="invoice_number" value="{{ old('invoice_number') }}" placeholder="Enter invoice number">
                                    @error('invoice_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Submission Date -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="submission_date">Submission Date</label>
                                    <input type="text" class="form-control @error('submission_date') is-invalid @enderror" name="submission_date" id="submission_date" value="{{ old('submission_date', date('Y-m-d')) }}" placeholder="Select submission date">
                                    @error('submission_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- PO Number -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="po_number">PO Number (Optional)</label>
                                    <input type="text" class="form-control @error('po_number') is-invalid @enderror" name="po_number" id="po_number" value="{{ old('po_number') }}" placeholder="Enter PO number">
                                    @error('po_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Invoice From Date -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="invoice_from_date">Invoice From Date</label>
                                    <input type="text" class="form-control @error('invoice_from_date') is-invalid @enderror" name="invoice_from_date" id="invoice_from_date" value="{{ old('invoice_from_date') }}" placeholder="Select invoice from date">
                                    @error('invoice_from_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Invoice To Date -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="invoice_to_date">Invoice To Date</label>
                                    <input type="text" class="form-control @error('invoice_to_date') is-invalid @enderror" name="invoice_to_date" id="invoice_to_date" value="{{ old('invoice_to_date') }}" placeholder="Select invoice to date">
                                    @error('invoice_to_date')
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
                                <div class="col-md-4">
                                    {{-- Display Vehicle Name/Identifier --}}
                                    <input type="text" class="form-control-plaintext item-vehicle-name bg-light" readonly placeholder="Vehicle">
                                    <input type="hidden" class="item-vehicle-id" name="items[0][vehicle_id]" disabled>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control item-working-hours bg-light" name="items[0][working_hours]" readonly placeholder="Hours" disabled>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control item-unit-price bg-light" name="items[0][unit_price]" readonly placeholder="Unit Price" disabled>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control item-total bg-light" name="items[0][total]" placeholder="Total" readonly disabled>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn" style="display:none;"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                            <!-- Initial Item Row (Labels for header) -->
                            <div class="row item-row mb-2">
                                <div class="col-md-4"><label>Vehicle</label></div>
                                <div class="col-md-2"><label>Working Hours</label></div>
                                <div class="col-md-2"><label>Unit Price</label></div>
                                <div class="col-md-2"><label>Total</label></div>
                                <div class="col-md-2"><label>&nbsp;</label></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="open-add-vehicle-item-modal-btn" disabled><i class="fa fa-truck"></i> Add Vehicle Item</button>

                        <!-- Totals -->
                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-6">
                                <div class="form-group row">
                                    <label for="subtotal" class="col-sm-4 col-form-label text-right">Subtotal</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext text-right fw-bold" id="subtotal" value="0.00">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax_rate" class="col-sm-4 col-form-label text-right">Tax Rate (%)</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control @error('tax_rate') is-invalid @enderror" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', 5) }}" placeholder="Enter tax rate" step="0.01" min="0">
                                        @error('tax_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax_amount" class="col-sm-4 col-form-label text-right">Tax Amount</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext text-right fw-bold" id="tax_amount" value="0.00">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="grand_total" class="col-sm-4 col-form-label text-right h4">Grand Total</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext text-right fw-bold h4" id="grand_total" name="amount" value="0.00">
                                        <input type="hidden" name="subtotal_amount" id="subtotal_amount_hidden" value="0">
                                        <input type="hidden" name="final_tax_amount" id="final_tax_amount_hidden" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group mt-3">
                            <label class="control-label mb-10" for="notes">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" rows="3" placeholder="Enter any notes for the invoice">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Invoice File -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="invoice_file">Invoice Document (Optional)</label>
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
                            <small class="form-text text-muted">Upload the main invoice document (PDF, DOC, DOCX, JPG, PNG).</small>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="status">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-wth-icon">
                                <span class="icon-label"><i class="fa fa-save"></i></span>
                                <span class="btn-text">Create Invoice</span>
                            </button>
                            <a href="{{ route('invoices.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Add Vehicle Item Modal -->
<div class="modal fade" id="addVehicleItemModal" tabindex="-1" role="dialog" aria-labelledby="addVehicleItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVehicleItemModalLabel">Add Vehicle to Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <input type="hidden" id="modal_item_vehicle_id"> --}}
                <div class="form-group">
                    <label for="modal_vehicle_select">Vehicle:</label>
                    <select class="form-control" id="modal_vehicle_select" style="width: 100%;">
                        <option value="">Select vehicle</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modal_item_working_hours">Working Hours</label>
                    <input type="number" class="form-control" id="modal_item_working_hours" placeholder="Enter working hours" min="0" step="0.01" disabled>
                </div>
                <div class="form-group">
                    <label for="modal_item_unit_price">Unit Price (per hour)</label>
                    <input type="number" class="form-control" id="modal_item_unit_price" placeholder="Enter unit price" min="0" step="0.01" disabled>
                </div>
                <div class="form-group">
                    <label>Line Total:</label>
                    <input type="text" class="form-control bg-light" id="modal_item_line_total" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveVehicleItemBtn" disabled>Add to Invoice</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        // --- BEGINNING OF REVISED DATE LOGIC FOR DD/MM/YYYY ---
        console.log('Initializing default invoice dates (DD/MM/YYYY)...');
        const today = new Date();
        const currentDay = today.getDate();
        const currentMonth = today.getMonth(); // 0-indexed (0 for January, 11 for December)
        const currentYear = today.getFullYear();

        let defaultInvoiceFromDate;
        let defaultInvoiceToDate;

        if (currentDay > 17) {
            defaultInvoiceFromDate = new Date(currentYear, currentMonth, 1);
            defaultInvoiceToDate = today;
            console.log('Date logic: currentDay > 17. From:', defaultInvoiceFromDate, 'To:', defaultInvoiceToDate);
        } else {
            defaultInvoiceFromDate = new Date(currentYear, currentMonth - 1, 1);
            defaultInvoiceToDate = new Date(currentYear, currentMonth, 0);
            console.log('Date logic: currentDay <= 17. From:', defaultInvoiceFromDate, 'To:', defaultInvoiceToDate);
        }

        const fromDateInput = $('#invoice_from_date');
        const toDateInput = $('#invoice_to_date');

        // Initialize Flatpickr for Invoice From Date
        let fromDateFlatpickrConfig = {
            dateFormat: "Y-m-d", // Format for the actual hidden input value
            altInput: true,        // Create a separate visible input
            altFormat: "d/m/Y",  // Format for the visible input (DD/MM/YYYY)
            allowInput: true       // Allow manual input in DD/MM/YYYY format
        };
        if ($.trim(fromDateInput.val()) === '' && defaultInvoiceFromDate) {
            console.log('Setting default Invoice From Date via Flatpickr:', defaultInvoiceFromDate);
            fromDateFlatpickrConfig.defaultDate = defaultInvoiceFromDate;
        } else {
            console.log('Invoice From Date already has a value or default date is invalid:', fromDateInput.val());
        }
        fromDateInput.flatpickr(fromDateFlatpickrConfig);

        // Initialize Flatpickr for Invoice To Date
        let toDateFlatpickrConfig = {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            allowInput: true
        };
        if ($.trim(toDateInput.val()) === '' && defaultInvoiceToDate) {
            console.log('Setting default Invoice To Date via Flatpickr:', defaultInvoiceToDate);
            toDateFlatpickrConfig.defaultDate = defaultInvoiceToDate;
        } else {
            console.log('Invoice To Date already has a value or default date is invalid:', toDateInput.val());
        }
        toDateInput.flatpickr(toDateFlatpickrConfig);

        // General Flatpickr for other date inputs (like submission_date)
        // Ensure submission_date also uses text input if you want DD/MM/YYYY display
        // For now, assuming submission_date is type="date" or type="text" and needs basic Y-m-d Flatpickr if type text
        $("#submission_date").flatpickr({
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            defaultDate: "today" // Or any other default logic you had
        });

        // --- END OF REVISED DATE LOGIC ---

        // Your existing flatpickr initialization for class .flatpickr might need review
        // if it was intended for these date fields. If they don't have .flatpickr class now,
        // that generic one won't apply to them.
        // flatpickr(".flatpickr", {
        //     dateFormat: "Y-m-d",
        //     altInput: true,
        //     altFormat: "F j, Y", // This was your previous format
        //     defaultDate: "today"
        // });

        // Initialize the main supplier selector
        $('#supplier_id').select2({
            placeholder: "Select Supplier"
        });

        // Modal's vehicle selector (initialized when modal opens)
        let $modalVehicleSelect = $('#modal_vehicle_select');

        // Enable/Disable 'Add Vehicle Item' button based on supplier selection
        $('#supplier_id').on('change', function() {
            const supplierId = $(this).val();
            $('#invoice-items-container .item-row:not(#item-row-template):not(:first-child)').remove(); // Clear existing items
            calculateGrandTotal();

            if (supplierId) {
                $('#open-add-vehicle-item-modal-btn').prop('disabled', false);
            } else {
                $('#open-add-vehicle-item-modal-btn').prop('disabled', true);
            }
            // Destroy previous select2 instance on modal selector if it exists, to re-init with new supplier
            if ($modalVehicleSelect.data('select2')) {
                $modalVehicleSelect.select2('destroy').empty().append('<option value="">Select vehicle</option>');
            }
            // Reset modal fields too
            resetModalFields();
        });

        function initializeModalVehicleSelector(supplierId) {
            if ($modalVehicleSelect.data('select2')) {
                $modalVehicleSelect.select2('destroy').empty().append('<option value="">Loading vehicles...</option>');
            }
            $modalVehicleSelect.select2({
                placeholder: "Select vehicle",
                dropdownParent: $('#addVehicleItemModal .modal-body'), // Important for select2 in modal
                ajax: {
                    url: `/suppliers/${supplierId}/vehicles`,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) { return { q: params.term, page: params.page }; },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: { more: (params.page * 30) < data.total_count }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
            }).on('select2:select', function() {
                // Enable fields when a vehicle is selected in the modal
                $('#modal_item_working_hours').prop('disabled', false);
                $('#modal_item_unit_price').prop('disabled', false);
                $('#saveVehicleItemBtn').prop('disabled', false);
            }).on('select2:unselect select2:clear', function() {
                resetModalFields(); // Disable fields if selection is cleared
            });
        }

        function resetModalFields() {
            $('#modal_item_working_hours').val('').prop('disabled', true);
            $('#modal_item_unit_price').val('').prop('disabled', true);
            $('#modal_item_line_total').val('');
            $('#saveVehicleItemBtn').prop('disabled', true);
            if ($modalVehicleSelect.data('select2')) {
                 // $modalVehicleSelect.val(null).trigger('change'); // this might auto-trigger unselect handler
            } else {
                $modalVehicleSelect.empty().append('<option value="">Select vehicle</option>');
            }
        }

        // Handle clicking 'Add Vehicle Item' button
        $('#open-add-vehicle-item-modal-btn').on('click', function() {
            const supplierId = $('#supplier_id').val();
            if (supplierId) {
                initializeModalVehicleSelector(supplierId);
                resetModalFields(); // Ensure fields are reset before showing
                 // Small delay to allow Select2 to initialize if it was destroyed
                setTimeout(function() {
                    $modalVehicleSelect.val(null).trigger('change'); // Clear previous selection in modal
                    $('#addVehicleItemModal').modal('show');
                }, 100);
            } else {
                alert('Please select a supplier first.');
            }
        });

        // Calculate line total in modal
        $('#modal_item_working_hours, #modal_item_unit_price').on('input', function() {
            const hours = parseFloat($('#modal_item_working_hours').val()) || 0;
            const price = parseFloat($('#modal_item_unit_price').val()) || 0;
            $('#modal_item_line_total').val((hours * price).toFixed(2));
        });

        let itemIndexGlobal = 0;

        // Handle saving item from modal
        $('#saveVehicleItemBtn').on('click', function() {
            const selectedVehicleData = $modalVehicleSelect.select2('data')[0];
            if (!selectedVehicleData || !selectedVehicleData.id) { alert('Please select a vehicle from the list.'); return; }

            const vehicleId = selectedVehicleData.id;
            const vehicleName = selectedVehicleData.text;
            const workingHours = parseFloat($('#modal_item_working_hours').val());
            const unitPrice = parseFloat($('#modal_item_unit_price').val());

            if (isNaN(workingHours) || workingHours <= 0) { alert('Please enter valid working hours.'); return; }
            if (isNaN(unitPrice) || unitPrice < 0) { alert('Please enter a valid unit price.'); return; }

            const newItemRow = $('#item-row-template').clone().removeAttr('id').removeClass('d-none');
            newItemRow.find('.item-vehicle-id').val(vehicleId);
            newItemRow.find('.item-vehicle-name').val(vehicleName);
            newItemRow.find('.item-working-hours').val(workingHours.toFixed(2));
            newItemRow.find('.item-unit-price').val(unitPrice.toFixed(2));
            newItemRow.find('.item-total').val((workingHours * unitPrice).toFixed(2));

            // Enable inputs in the cloned row
            newItemRow.find('input[name^="items["]').prop('disabled', false);

            $('#invoice-items-container').append(newItemRow);
            updateRowIndices();
            calculateGrandTotal();

            $('#addVehicleItemModal').modal('hide');
            // No need to clear $vehicleSelectorForItem as it does not exist anymore
            // Reset modal for next use
            if ($modalVehicleSelect.data('select2')) {
                 $modalVehicleSelect.val(null).trigger('change');
            }
            resetModalFields();
        });

        function updateRowIndices() {
            let currentIndex = 0;
            $('#invoice-items-container .item-row:not(#item-row-template):not(:first-child)').each(function() {
                $(this).find('input, select, textarea').each(function() {
                    if (this.name) {
                        this.name = this.name.replace(/items\[\d+\]/, `items[${currentIndex}]`);
                    }
                });
                $(this).find('.remove-item-btn').show();
                currentIndex++;
            });
            itemIndexGlobal = currentIndex; // Update global index for next potential item (if we were using it)
        }

        // Remove item row
        $('#invoice-items-container').on('click', '.remove-item-btn', function() {
            $(this).closest('.item-row').remove();
            updateRowIndices();
            calculateGrandTotal();
        });

        // Grand total calculation
        function calculateGrandTotal() {
            let subtotal = 0;
            $('#invoice-items-container .item-row:not(#item-row-template):not(:first-child)').each(function() {
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

        $('#tax_rate').on('input', function() {
            calculateGrandTotal();
        });

        // Initial setup
        if (!$('#supplier_id').val()) {
            $('#open-add-vehicle-item-modal-btn').prop('disabled', true);
        }
        updateRowIndices();
        calculateGrandTotal();

        // Handling for old input if form validation fails
        const oldItems = {!! json_encode(old('items', [])) !!};
        if (oldItems.length > 0 && $('#supplier_id').val()) {
            const supplierIdForOldItems = $('#supplier_id').val();
            // To get vehicle names for old items, we need to fetch them.
            // This is an advanced scenario. For now, we'll just use IDs as placeholders.
            // A better way: Collect all vehicle_ids from oldItems, make one AJAX call to get their names.
            let vehicleNamePromises = [];
            let vehicleNamesMap = {};

            // Create a map of vehicle IDs to their data from oldItems
            const oldItemDetailsMap = oldItems.reduce((map, item) => {
                map[item.vehicle_id] = item;
                return map;
            }, {});

            // Fetch vehicle names if supplier is present
            if(supplierIdForOldItems) {
                const vehicleIds = oldItems.map(item => item.vehicle_id).filter(id => id != null);
                if (vehicleIds.length > 0) {
                    // Assuming your endpoint can handle a request for specific IDs if needed
                    // or we just rely on the general supplier vehicle list and match later.
                    // For simplicity, we will fetch all vehicles for the supplier and then find names.
                    vehicleNamePromises.push(
                        $.ajax({
                            url: `/suppliers/${supplierIdForOldItems}/vehicles`,
                            dataType: 'json'
                        }).done(function(data) {
                            if(data.results) {
                                data.results.forEach(vehicle => {
                                    vehicleNamesMap[vehicle.id] = vehicle.text;
                                });
                            }
                        })
                    );
                }
            }

            $.when.apply($, vehicleNamePromises).done(function() {
                oldItems.forEach(function(oldItem, index) {
                    let vehicleName = vehicleNamesMap[oldItem.vehicle_id] || `Vehicle ID: ${oldItem.vehicle_id}`;

                    const newItemRow = $('#item-row-template').clone().removeAttr('id').removeClass('d-none');
                    newItemRow.find('.item-vehicle-id').val(oldItem.vehicle_id);
                    newItemRow.find('.item-vehicle-name').val(vehicleName);
                    newItemRow.find('.item-working-hours').val(parseFloat(oldItem.working_hours || 0).toFixed(2));
                    newItemRow.find('.item-unit-price').val(parseFloat(oldItem.unit_price || 0).toFixed(2));
                    const lineTotal = (parseFloat(oldItem.working_hours || 0) * parseFloat(oldItem.unit_price || 0));
                    newItemRow.find('.item-total').val(lineTotal.toFixed(2));

                    // Enable inputs in the cloned row for old items
                    newItemRow.find('input[name^="items["]').prop('disabled', false);

                    $('#invoice-items-container').append(newItemRow);
                });
                updateRowIndices();
                calculateGrandTotal();
            }).fail(function() {
                // Fallback if AJAX fails for names
                 oldItems.forEach(function(oldItem, index) {
                    let vehicleName = `Vehicle ID: ${oldItem.vehicle_id} (Name lookup failed)`;
                    const newItemRow = $('#item-row-template').clone().removeAttr('id').removeClass('d-none');
                    newItemRow.find('.item-vehicle-id').val(oldItem.vehicle_id);
                    newItemRow.find('.item-vehicle-name').val(vehicleName);
                    newItemRow.find('.item-working-hours').val(parseFloat(oldItem.working_hours || 0).toFixed(2));
                    newItemRow.find('.item-unit-price').val(parseFloat(oldItem.unit_price || 0).toFixed(2));
                    const lineTotal = (parseFloat(oldItem.working_hours || 0) * parseFloat(oldItem.unit_price || 0));
                    newItemRow.find('.item-total').val(lineTotal.toFixed(2));

                    // Enable inputs in the cloned row for old items (fallback)
                    newItemRow.find('input[name^="items["]').prop('disabled', false);

                    $('#invoice-items-container').append(newItemRow);
                });
                updateRowIndices();
                calculateGrandTotal();
            });
        } else {
            $('#invoice-items-container .item-row:not(#item-row-template):not(:first-child)').remove();
        }

    });
</script>
@endpush
