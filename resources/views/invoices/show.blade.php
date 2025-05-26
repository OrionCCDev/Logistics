@extends('layouts.app')

@section('page_title', 'Invoice Details')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('invoices.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Invoices
    </a>
</div>
<a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-pencil"></i></span>
    <span class="btn-text">Edit Invoice</span>
</a>
<button type="button" class="btn btn-sm btn-danger btn-rounded btn-wth-icon icon-wthot-bg mb-15 mx-2" data-toggle="modal" data-target="#deleteInvoiceModal">
    <span class="icon-label"><i class="fa fa-trash"></i></span>
    <span class="btn-text">Delete Invoice</span>
</button>
@endsection

@section('content')
<div class="hk-row" id="invoice-show-page">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <div class="row justify-content-center">
                <div class="col-md-10 text-center mb-4">
                    <h2 class="mt-4 mb-2">Invoice #{{ $invoice->invoice_number }}</h2>
                    <span class="badge {{ $invoice->status === 'paid' ? 'badge-success' : ($invoice->status === 'pending' ? 'badge-warning' : ($invoice->status === 'overdue' ? 'badge-danger' : 'badge-secondary')) }} badge-pill px-3 py-2" style="font-size: 0.9rem;">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-info-circle text-primary me-2"></i>
                                Invoice Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group mb-3">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-muted" style="width: 120px;">Supplier:</div>
                                            <div class="flex-grow-1 fw-bold">{{ $invoice->supplier->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="info-group mb-3">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-muted" style="width: 120px;">Invoice Date:</div>
                                            <div class="flex-grow-1">{{ $invoice->submission_date ? $invoice->submission_date->format('M d, Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                    {{-- <div class="info-group mb-3">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-muted" style="width: 120px;">Due Date:</div>
                                            <div class="flex-grow-1">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</div>
                                        </div>
                                    </div> --}}

                                </div>
                                <div class="col-md-6">
                                    <div class="info-group mb-3">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-muted" style="width: 120px;">Amount:</div>
                                            <div class="flex-grow-1 fw-bold">{{ number_format($invoice->total_amount, 2) }} <span class="text-muted small">{{ $invoice->currency_code }}</span></div>
                                        </div>
                                    </div>
                                    @if($invoice->po_number)
                                    <div class="info-group mb-3">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-muted" style="width: 120px;">PO Number:</div>
                                            <div class="flex-grow-1">{{ $invoice->po_number }}</div>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                                <div class="col-12">
                                    @if($invoice->invoice_from_date)
                                    <div class="info-group mb-3">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-muted" style="width: 120px;">From:</div>
                                            <div class="flex-grow-1 fw-bold">{{ $invoice->invoice_from_date ? $invoice->invoice_from_date->format('M d, Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($invoice->invoice_to_date)
                                    <div class="info-group mb-3">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-muted" style="width: 120px;">To:</div>
                                            <div class="flex-grow-1">{{ $invoice->invoice_to_date ? $invoice->invoice_to_date->format('M d, Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($invoice->invoice_file_path)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-file-text-o text-primary me-2"></i>
                                Invoice Document
                            </h5>
                        </div>
                        <div class="card-body">
                            <p>
                                <a href="{{ asset($invoice->invoice_file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-download me-1"></i> View/Download Document
                                </a>
                                <span class="ms-2 text-muted">({{ basename($invoice->invoice_file_path) }})</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($invoice->vehicles && $invoice->vehicles->count() > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-truck text-primary me-2"></i>
                                Invoice Vehicle Details
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Vehicle</th>
                                            <th>Type</th>
                                            <th class="text-right">Total Hours</th>
                                            <th class="text-right">Cost (w/o Tax)</th>
                                            <th class="text-right">Cost (w/ Tax)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $invoiceSubtotal = 0;
                                            $invoiceTaxAmount = 0;
                                            $invoiceTotalAmount = 0;
                                        @endphp
                                        @foreach($invoice->vehicles as $vehicle)
                                        <tr>
                                            <td>
                                                {{ $vehicle->plate_number }} ({{ $vehicle->vehicle_model }})
                                                @if($vehicle->vehicle_image)
                                                <a href="{{ asset('dashAssets/uploads/vehicles/' . $vehicle->vehicle_image) }}" data-toggle="lightbox" data-title="{{ $vehicle->plate_number }}">
                                                    <i class="fa fa-image ml-1"></i>
                                                </a>
                                                @endif
                                            </td>
                                            <td>{{ $vehicle->vehicle_type }}</td>
                                            <td class="text-right">{{ $vehicle->pivot->total_hours ?? '0.00' }}</td>
                                            <td class="text-right">{{ number_format($vehicle->pivot->total_cost_without_tax ?? 0, 2) }} {{ $invoice->currency_symbol ?? 'AED' }}</td>
                                            <td class="text-right">{{ number_format($vehicle->pivot->total_cost_with_tax ?? 0, 2) }} {{ $invoice->currency_symbol ?? 'AED' }}</td>
                                        </tr>
                                        @php
                                            $invoiceSubtotal += $vehicle->pivot->total_cost_without_tax ?? 0;
                                            // Assuming tax_amount per vehicle is total_cost_with_tax - total_cost_without_tax
                                            $invoiceTaxAmount += ($vehicle->pivot->total_cost_with_tax ?? 0) - ($vehicle->pivot->total_cost_without_tax ?? 0);
                                            $invoiceTotalAmount += $vehicle->pivot->total_cost_with_tax ?? 0;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">Subtotal</td>
                                            <td class="text-right font-weight-bold">{{ number_format($invoiceSubtotal, 2) }} {{ $invoice->currency_symbol ?? 'AED' }}</td>
                                        </tr>
                                        {{-- Display overall invoice tax if available, otherwise sum of vehicle taxes --}}
                                        @if($invoice->tax_rate > 0 || $invoiceTaxAmount > 0)
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">Tax {{ $invoice->tax_rate > 0 ? '(' . $invoice->tax_rate . '%)' : '' }}</td>
                                            <td class="text-right font-weight-bold">{{ $invoice->currency_symbol ?? '$' }}{{ number_format($invoice->tax_amount > 0 ? $invoice->tax_amount : $invoiceTaxAmount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold h4">Grand Total</td>
                                            <td class="text-right font-weight-bold h4">{{ $invoice->currency_symbol ?? '$' }}{{ number_format($invoice->total_amount > 0 ? $invoice->total_amount : $invoiceTotalAmount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($invoice->notes)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-pencil-square-o text-primary me-2"></i>
                                Notes / Description
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $invoice->notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($invoice->attachments && $invoice->attachments->count() > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-paperclip text-primary me-2"></i>
                                Attachments
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($invoice->attachments as $attachment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ $attachment->file_path }}" target="_blank">{{ $attachment->file_name }}</a>
                                    <span class="badge badge-light">{{ $attachment->file_size }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </section>
    </div>
</div>

<!-- Delete Invoice Modal -->
<div class="modal fade" id="deleteInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteInvoiceModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete invoice <strong>#{{ $invoice->invoice_number }}</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Invoice</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

