@extends('layouts.app')

@section('page_title', 'Branches Management')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <button type="button" class="btn btn-outline-primary">Branches</button>
</div>
<a href="{{ route('branches.create') }}"
    class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-plus"></i></span>
    <span class="btn-text">New Branch</span>
</a>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <div class="row">
                <div class="col-sm">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($branches as $branch)
                                    <tr>
                                        <td>{{ $branch->name }}</td>
                                        <td>{{ $branch->code }}</td>
                                        <td>{{ $branch->address }}</td>
                                        <td>{{ $branch->phone }}</td>
                                        <td>
                                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                                <a href="{{ route('branches.show', $branch->id) }}" class="btn btn-success">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-info">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteBranchModal{{ $branch->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Delete Branch Modal -->
                                            <div class="modal fade" id="deleteBranchModal{{ $branch->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteBranchModalLabel{{ $branch->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteBranchModalLabel{{ $branch->id }}">
                                                                <i class="fa fa-exclamation-triangle text-danger me-2"></i>
                                                                Confirm Delete
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete the branch <strong>"{{ $branch->name }}"</strong>?</p>
                                                            <p class="text-danger mb-0">This action cannot be undone.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Delete Branch</button>
                                                            </form>
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
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('dashAssets/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/jszip/dist/jszip.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('dashAssets/dist/js/dataTables-data.js') }}"></script>
@endsection

@section('forceScripts')
<style>
    table tr td:last-child {
        display:block !important
    }
</style>
@endsection
