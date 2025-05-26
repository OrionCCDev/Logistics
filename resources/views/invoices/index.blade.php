@extends('layouts.app')

@section('page_title', 'Invoices')

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Invoices</h2>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add New Invoice
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Invoice #</th>
                            <th>Supplier</th>
                            <th>Amount</th>
                            {{-- <th>Due Date</th> --}}
                            <th>Vehicles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>
                                    {{ $invoice->submission_date?->format('M d, Y') }}
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $invoice->invoice_number }}</div>
                                </td>
                                <td>
                                    @if($invoice->supplier)
                                        {{ $invoice->supplier->name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $invoice->currency_symbol }}{{ number_format($invoice->total_amount, 2) }}</div>
                                    <div class="text-muted small">{{ $invoice->currency_code }}</div>
                                </td>
                                {{--<td>
                                    {{ $invoice->due_date?->format('M d, Y') }}
                                </td>--}}
                                <td>
                                    @if($invoice->vehicles->count() > 0)
                                        @foreach($invoice->vehicles as $vehicle)
                                            <div>{{ $vehicle->plate_number }}</div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-success btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-pencil"></i>
                                        </a>

                                        <button  data-toggle="modal" data-target="#deleteInvoiceModal{{ $invoice->id }}" class="btn btn-danger btn-sm" >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <div class="modal fade" id="deleteInvoiceModal{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteInvoiceModalLabel" aria-hidden="true">
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
                                                        <p>Are you sure you want to delete the invoice <strong>"{{ $invoice->invoice_number }}"</strong>?</p>
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
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $invoices->links() }}
            </div>
        </section>
    </div>
</div>
@endsection


@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <button type="button" class="btn btn-outline-primary">Invoices</button>
</div>
<a href="{{ route('invoices.create') }}"
    class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-plus"></i></span>
    <span class="btn-text">New Invoice</span>
</a>
@endsection
