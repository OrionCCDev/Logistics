@extends('layouts.app')

@section('page_title', 'System Analytics')

@section('content')
<div class="container-fluid">
    <!-- Info Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card card-sm shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl avatar-rounded bg-primary-light-5 text-primary me-3">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $supplierCount }}</h5>
                            <span class="text-muted">Total Suppliers</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-sm shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl avatar-rounded bg-success-light-5 text-success me-3">
                            <i class="fas fa-car fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $vehicleCount }}</h5>
                            <span class="text-muted">Total Vehicles</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-sm shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl avatar-rounded bg-info-light-5 text-info me-3">
                            <i class="fas fa-project-diagram fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $projectVehicleCount }}</h5>
                            <span class="text-muted">Project Vehicles</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
             <div class="card card-sm shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl avatar-rounded bg-warning-light-5 text-warning me-3">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $mostVehiclesSupplier ? $mostVehiclesSupplier->name : 'N/A' }}</h5>
                            <span class="text-muted">Supplier with Most Vehicles ({{ $mostVehiclesSupplier ? $mostVehiclesSupplier->vehicles_count : 0 }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="row">
        <!-- Weekly Fuel Consumption -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 5 Vehicles - Weekly Fuel Consumption</h5>
                </div>
                <div class="card-body">
                    @if($weeklyFuelConsumption->isEmpty())
                        <p class="text-muted">No fuel consumption data available for this week.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($weeklyFuelConsumption as $consumption)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $consumption->vehicle ? $consumption->vehicle->name : 'Unknown Vehicle' }} ({{ $consumption->vehicle ? $consumption->vehicle->plate_number : 'N/A' }})
                                    <span class="badge bg-primary rounded-pill">{{ number_format($consumption->total_fuel, 2) }} Liters</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Monthly Fuel Consumption -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 5 Vehicles - Monthly Fuel Consumption</h5>
                </div>
                <div class="card-body">
                     @if($monthlyFuelConsumption->isEmpty())
                        <p class="text-muted">No fuel consumption data available for this month.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($monthlyFuelConsumption as $consumption)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $consumption->vehicle ? $consumption->vehicle->name : 'Unknown Vehicle' }} ({{ $consumption->vehicle ? $consumption->vehicle->plate_number : 'N/A' }})
                                    <span class="badge bg-success rounded-pill">{{ number_format($consumption->total_fuel, 2) }} Liters</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Vehicles per Supplier -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vehicles per Supplier</h5>
                </div>
                <div class="card-body">
                    @if($vehiclesPerSupplier->isEmpty())
                        <p class="text-muted">No supplier data available.</p>
                    @else
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Supplier Name</th>
                                        <th>Vehicle Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehiclesPerSupplier as $supplier)
                                        <tr>
                                            <td>{{ $supplier->name }}</td>
                                            <td>{{ $supplier->vehicles_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Project Fuel Consumption -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 5 Projects - Monthly Fuel Consumption</h5>
                </div>
                <div class="card-body">
                    @if($projectFuelConsumption->isEmpty())
                        <p class="text-muted">No project fuel consumption data available for this month.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($projectFuelConsumption as $project)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $project['name'] }}
                                    <span class="badge bg-info rounded-pill">{{ number_format($project['total_fuel'], 2) }} Liters</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Vehicles per Supplier Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vehicles per Supplier (Chart)</h5>
                </div>
                <div class="card-body">
                    <div id="vehiclesPerSupplierChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        <!-- Monthly Fuel Consumption Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 5 Vehicles - Monthly Fuel Consumption (Chart)</h5>
                </div>
                <div class="card-body">
                    <div id="monthlyFuelChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Project Fuel Consumption Chart -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 5 Projects - Monthly Fuel Consumption (Chart)</h5>
                </div>
                <div class="card-body">
                    <div id="projectFuelChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .avatar-rounded {
        border-radius: 0.75rem; /* More modern rounded corners */
    }
    .card-sm .card-body {
        padding: 1.25rem;
    }
    .bg-primary-light-5 { background-color: rgba(99, 102, 241, 0.15); }
    .bg-success-light-5 { background-color: rgba(22, 163, 74, 0.15); }
    .bg-info-light-5 { background-color: rgba(59, 130, 246, 0.15); }
    .bg-warning-light-5 { background-color: rgba(245, 158, 11, 0.15); }
</style>
@endpush

@push('scripts')
<script src="{{ asset('dashAssets/vendors/echarts/echarts.min.js') }}"></script>
<script>
    // Vehicles per Supplier Chart
    var vpsChartDom = document.getElementById('vehiclesPerSupplierChart');
    var vpsChart = echarts.init(vpsChartDom);
    var vpsOption = {
        tooltip: {},
        xAxis: {
            type: 'category',
            data: @json($vehiclesPerSupplierChart['labels'])
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data: @json($vehiclesPerSupplierChart['data']),
            type: 'bar',
            itemStyle: { color: '#6366f1' }
        }]
    };
    vpsChart.setOption(vpsOption);

    // Monthly Fuel Consumption Chart
    var mfcChartDom = document.getElementById('monthlyFuelChart');
    var mfcChart = echarts.init(mfcChartDom);
    var mfcOption = {
        tooltip: {},
        xAxis: {
            type: 'category',
            data: @json($monthlyFuelChart['labels'])
        },
        yAxis: {
            type: 'value',
            name: 'Liters'
        },
        series: [{
            data: @json($monthlyFuelChart['data']),
            type: 'bar',
            itemStyle: { color: '#16a34a' }
        }]
    };
    mfcChart.setOption(mfcOption);

    // Project Fuel Consumption Chart
    var pfcChartDom = document.getElementById('projectFuelChart');
    var pfcChart = echarts.init(pfcChartDom);
    var pfcOption = {
        tooltip: {},
        xAxis: {
            type: 'category',
            data: @json($projectFuelChart['labels'])
        },
        yAxis: {
            type: 'value',
            name: 'Liters'
        },
        series: [{
            data: @json($projectFuelChart['data']),
            type: 'bar',
            itemStyle: { color: '#3b82f6' }
        }]
    };
    pfcChart.setOption(pfcOption);
</script>
@endpush
