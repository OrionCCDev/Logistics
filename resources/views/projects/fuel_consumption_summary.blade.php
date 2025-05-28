@extends('layouts.app')

@section('page_title', 'Fuel Consumption Summary for ' . $project->name)

@section('styles')
<style>
    /* Simple screen styles - no complex print CSS needed */
    .print-logo {
        display: none;
    }

    .print-only {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <div class="row mb-25">
                <div class="col-sm-6">
                    <h5 class="hk-sec-title">Fuel Consumption Summary - {{ $project->name }} (This Month)</h5>
                </div>
                <div class="col-sm-6 text-right">
                    <button onclick="window.open('{{ route('projects.print_fuel_consumption_summary', ['project' => $project->id]) }}', '_blank');" class="btn btn-primary btn-sm">Print Summary</button>
                </div>
            </div>

            <div id="print-section">
                <!-- Logo - only visible when printing -->
                <img width="150" height="75" src="{{ asset('dashAssets/dist/img/logo-light.png') }}" alt="Company Logo" class="print-logo">

                <!-- Title - only visible when printing -->
                <h5 class="print-only print-title">Fuel Consumption Summary - {{ $project->name }} (This Month)</h5>

                <!-- Total consumption - visible on screen, styled differently for print -->
                <div class="mb-25 print-total">
                    <strong>Total Fuel Consumption: {{ $totalFuelConsumption }} Liters</strong><br>
                    <strong>Total Working Hours: {{ $totalWorkingHours }} Hours</strong>
                </div>

                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Vehicle</th>
                                            <th>Fuel Consumed</th>
                                            <th>Total Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($timesheets as $timesheet)
                                        <tr>
                                            <td>{{ $timesheet->date->format('Y-m-d') }}</td>
                                            <td>{{ $timesheet->vehicle ? $timesheet->vehicle->name : 'N/A' }} ({{ $timesheet->vehicle ? $timesheet->vehicle->plate_number : 'N/A' }})</td>
                                            <td>{{ $timesheet->fuel_consumption }}</td>
                                            <td>{{ $timesheet->working_hours }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No fuel consumption records found for this project this month.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    // Optional: Add print event listeners for additional customization (can be kept if desired)
    window.addEventListener('beforeprint', function() {
        console.log('Main page: beforeprint event triggered (likely by new print window)');
    });

    window.addEventListener('afterprint', function() {
        console.log('Main page: afterprint event triggered (print dialog from new window closed)');
    });
</script>
@endsection