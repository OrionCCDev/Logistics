@extends('layouts.app')

@section('page_title', 'Categories Management')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <button type="button" class="btn btn-outline-primary">Categories</button>
</div>
<a href="{{ route('categories.create') }}"
    class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-plus"></i></span>
    <span class="btn-text">New Category</span>
</a>
@endsection

@section('content')
<style>
    table.dataTable.hover tbody tr:hover, table.dataTable.display tbody tr:hover {
        background-color: #527fdf;
        color: #fff;
    }
</style>
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <div class="row">
                <div class="col-sm">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table id="datable_3" class="table table-hover w-100 display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Suppliers</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td><span class="badge badge-primary">{{ $category->suppliers->count() }}</span></td>

                                        <td>
                                            <div class="btn-group mr-2" role="group" aria-label="Category actions">
                                                <a href="{{ route('categories.show', $category) }}" class="btn btn-success btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-pencil"></i>
                                                </a>

                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteCategoryModal{{ $category->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
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


@section('forceScripts')
<style>
    table tr td:last-child {
        display:block !important
    }
</style>
@endsection
