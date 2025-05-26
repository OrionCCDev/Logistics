@extends('layouts.app')

@section('page_title', 'Orion Users')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <button type="button" class="btn btn-outline-primary">Users</button>
</div>
<a href="{{ route('users.create') }}"
    class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-plus"></i></span>
    <span class="btn-text">New User</span>
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
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Position</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->employee->emp_code }}</td>
                                        <td>
                                            @if($user->employee && $user->employee->image)
                                                <img src="{{ asset($user->employee->image) }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                            @else
                                                <img src="{{ asset('dashAssets/img/avatar-placeholder.png') }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                            @endif
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            @if($user->employee && $user->employee->projects->count() > 0)
                                                @foreach ($user->employee->projects as $project)
                                                <span class="badge badge-success badge-pill">
                                                    {{ $project->code }}
                                                </span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-warning badge-pill">
                                                    No Projects Assigned
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <div class="btn-group mr-2" role="group" aria-label="User actions">
                                                <a href="{{ route('users.show', $user) }}" class="btn btn-success btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
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
