@extends('layouts.app')

@section('page_title', 'Operators')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Operators</h3>
                    <div class="card-tools">
                        <a href="{{ route('operators.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add New Operator
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>

                                    <th>Name</th>
                                    <th>Supplier</th>


                                    <th>License Expiry</th>

                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($operators as $operator)
                                    <tr>
                                        <td>{{ $operator->id }}</td>

                                        <td>{{ $operator->name }}</td>
                                        <td>{{ $operator->supplier->name ?? 'N/A' }}</td>


                                        <td>{{ $operator->license_expiry_date ? $operator->license_expiry_date->format('Y-m-d') : 'N/A' }}</td>

                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('operators.show', $operator) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('operators.edit', $operator) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-pencil"></i>
                                                </a>

                                                <button type="submit" class="btn btn-danger btn-sm" >
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No operators found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $operators->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <button type="button" class="btn btn-outline-primary">Operators</button>
</div>
<a href="{{ route('operators.create') }}"
    class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-plus"></i></span>
    <span class="btn-text">New Operator</span>
</a>
@endsection
