@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Filters Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Vehicle Utilization Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.vehicle-utilization') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="date_from">Date From</label>
                                <input type="date" class="form-control" name="date_from"
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to">Date To</label>
                                <input type="date" class="form-control" name="date_to"
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="supplier_id">Supplier</label>
                                <select class="form-control" name="supplier_id">
                                    <option value="">All Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-filter"></i> Apply Filters
                                    </button>
                                    <a href="{{ route('reports.vehicle-utilization') }}" class="btn btn-secondary">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Table -->
            <x-reports-table
                table-id="vehicleUtilizationTable"
                title="Vehicle Utilization Report"
                search-fields="Vehicle, Supplier, Project"
                export-filename="vehicle_utilization_report"
                :columns="[
                    ['title' => 'Vehicle'],
                    ['title' => 'Supplier'],
                    ['title' => 'Total Hours', 'className' => 'text-right'],
                    ['title' => 'Total Fuel', 'className' => 'text-right'],
                    ['title' => 'Avg Efficiency', 'className' => 'text-right'],
                    ['title' => 'Projects Used'],
                    ['title' => 'Utilization %', 'className' => 'text-right'],
                    ['title' => 'Status']
                ]">
                @forelse($vehicleStats as $stat)
                    <tr data-search="{{ strtolower($stat->vehicle_plate . ' ' . $stat->supplier_name . ' ' . $stat->projects_list) }}">
                        <td>{{ $stat->vehicle_plate }}</td>
                        <td>{{ $stat->supplier_name }}</td>
                        <td>{{ number_format($stat->total_hours, 2) }}</td>
                        <td>{{ number_format($stat->total_fuel, 2) }}</td>
                        <td>{{ $stat->total_hours > 0 ? number_format($stat->total_fuel / $stat->total_hours, 2) : 'N/A' }}</td>
                        <td>{{ $stat->projects_count }} projects</td>
                        <td>
                            @php
                                $utilization = ($stat->total_hours / (30 * 8)) * 100; // Assuming 30 days, 8 hours per day
                            @endphp
                            <span class="badge badge-{{ $utilization > 80 ? 'success' : ($utilization > 50 ? 'warning' : 'danger') }}">
                                {{ number_format($utilization, 1) }}%
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $stat->is_active ? 'success' : 'secondary' }}">
                                {{ $stat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No vehicle utilization data found.</td>
                    </tr>
                @endforelse
            </x-reports-table>
        </div>
    </div>
</div>
@endsection
