@extends('layouts.app')

@section('page_title', 'Category Details')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('categories.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Categories
    </a>
</div>
<a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-pencil"></i></span>
    <span class="btn-text">Edit Category</span>
</a>
<button type="button" class="btn btn-sm btn-danger btn-rounded btn-wth-icon icon-wthot-bg mb-15 mx-2" data-toggle="modal" data-target="#deleteCategoryModal">
    <span class="icon-label"><i class="fa fa-trash"></i></span>
    <span class="btn-text">Delete Category</span>
</button>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center mb-4">
                    <div class="category-header">
                        <h2 class="mt-4 mb-2">{{ $category->name }}</h2>
                        <p class="text-muted">{{ $category->suppliers->count() }} suppliers</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-info-circle text-primary me-2"></i>
                                Category Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="mb-3">Suppliers in this Category</h6>
                                    @if($category->suppliers->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($category->suppliers as $supplier)
                                                        <tr>
                                                            <td>{{ $supplier->name }}</td>
                                                            <td>{{ $supplier->email }}</td>
                                                            <td>{{ $supplier->phone }}</td>
                                                            <td>
                                                                <span class="badge badge-{{ $supplier->is_active ? 'success' : 'danger' }}">
                                                                    {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-sm btn-info">
                                                                    <i class="fa fa-eye"></i> View
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle me-2"></i>
                                            No suppliers found in this category.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category <strong>"{{ $category->name }}"</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

