@extends('layouts.app')

@section('page_title', 'Compare Reports Results')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Compare Timesheet Reports Results</div>

                <div class="card-body">
                    {{-- Check if both collections are empty --}}
                    @if($authenticatedUserTimesheets->isEmpty() && $documentControllerTimesheets->isEmpty())
                        <p>No timesheet data found for the selected criteria.</p>
                    @else
                        <p>Comparison Results for {{ $documentController->name }} (Document Controller) and {{ Auth::user()->name }} (Authenticated User) for Project: {{ $project->name }}</p>
                        <p>Date Range: {{ $startDate->format('Y-m-d') }} to {{ $endDate->format('Y-m-d') }}</p>

                        {{-- Group timesheets by date and vehicle --}}
                        @php
                            $allTimesheets = $authenticatedUserTimesheets->merge($documentControllerTimesheets);
                            $groupedTimesheets = $allTimesheets->groupBy(function($item) {
                                return $item->date->format('Y-m-d') . '_' . $item->vehicle_id; // Assuming 'date' is a Carbon instance and 'vehicle_id' exists
                            });
                        @endphp

                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Vehicle</th>
                                    <th>User</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Total Working Hour</th>
                                    <th>Total Fuel Consumption</th>
                                    <th>Checked</th>
                                    {{-- Add other columns you want to compare --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedTimesheets as $groupKey => $timesheetEntries)
                                    @php
                                        $docControllerEntry = $timesheetEntries->where('user_id', $documentController->id)->first();
                                        $authUserEntry = $timesheetEntries->where('user_id', Auth::id())->first();
                                        $date = \Carbon\Carbon::parse(explode('_', $groupKey)[0])->format('Y-m-d');

                                        // Calculate Working Hours (assuming start_time and end_time columns exist and are parseable by Carbon)
                                        $docControllerWorkingHours = null;
                                        if ($docControllerEntry && $docControllerEntry->working_start_hour && $docControllerEntry->working_end_hour) {
                                            try {
                                                $start = \Carbon\Carbon::parse($docControllerEntry->working_start_hour);
                                                $end = \Carbon\Carbon::parse($docControllerEntry->working_end_hour);
                                                $docControllerWorkingHours = $end->diffInMinutes($start) / 60; // Calculate in hours
                                            } catch (\Exception $e) {
                                                $docControllerWorkingHours = 'Invalid time format';
                                            }
                                        }

                                        $authUserWorkingHours = null;
                                        if ($authUserEntry && $authUserEntry->working_start_hour && $authUserEntry->working_end_hour) {
                                             try {
                                                $start = \Carbon\Carbon::parse($authUserEntry->working_start_hour);
                                                $end = \Carbon\Carbon::parse($authUserEntry->working_end_hour);
                                                $authUserWorkingHours = $end->diffInMinutes($start) / 60; // Calculate in hours
                                            } catch (\Exception $e) {
                                                $authUserWorkingHours = 'Invalid time format';
                                            }
                                        }

                                        // Get Fuel Consumption (assuming fuel_consumed column exists)
                                        $docControllerFuel = $docControllerEntry ? ($docControllerEntry->fuel_consumption ?? 'N/A') : 'N/A';
                                        $authUserFuel = $authUserEntry ? ($authUserEntry->fuel_consumption ?? 'N/A') : 'N/A';

                                        // Determine text color classes
                                        $workingHoursClass = ($docControllerWorkingHours !== null && $authUserWorkingHours !== null && abs($docControllerWorkingHours) === abs($authUserWorkingHours)) ? 'text-success' : 'text-danger';
                                        $fuelClass = ($docControllerFuel !== 'N/A' && $authUserFuel !== 'N/A' && $docControllerFuel === $authUserFuel) ? 'text-success' : 'text-danger';

                                        // Get Vehicle details
                                        $vehicleTypePlate = 'N/A';
                                        if ($docControllerEntry && $docControllerEntry->vehicle) {
                                            $vehicleTypePlate = ($docControllerEntry->vehicle->vehicle_type ?? 'N/A') . ' - ' . ($docControllerEntry->vehicle->plate_number ?? 'N/A');
                                        } elseif ($authUserEntry && $authUserEntry->vehicle) {
                                             // Fallback in case doc controller entry is missing but auth user entry exists for the same vehicle
                                            $vehicleTypePlate = ($authUserEntry->vehicle->vehicle_type ?? 'N/A') . ' - ' . ($authUserEntry->vehicle->plate_number ?? 'N/A');
                                        }

                                        // Determine if Document Controller's row should be highlighted red
                                        $docControllerRowClass = '';
                                        // Highlight if authenticated user has an entry AND document controller's entry is missing or does not match
                                        if ($authUserEntry && (!$docControllerEntry || (abs($docControllerWorkingHours ?? 0) !== abs($authUserWorkingHours ?? 0) || ($docControllerFuel ?? 'N/A') !== ($authUserFuel ?? 'N/A')))) {
                                            $docControllerRowClass = 'table-danger';
                                        }
                                    @endphp

                                    {{-- Document Controller's row --}}
                                    <tr class="{{ $docControllerRowClass }}">
                                        <td rowspan="2">{{ $date }}</td>
                                        <td rowspan="2">{{ $vehicleTypePlate }}</td> {{-- Display Vehicle Type and Plate --}}
                                        <td>{{ $documentController->name }}</td>
                                        <td>{{ $docControllerEntry ? $docControllerEntry->working_start_hour->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td>{{ $docControllerEntry ? $docControllerEntry->working_end_hour->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td class="{{ $workingHoursClass }}">{{ $docControllerWorkingHours !== null ? number_format(abs($docControllerWorkingHours), 2) . ' hrs' : 'N/A' }}</td>
                                        <td class="{{ $fuelClass }}">{{ $docControllerFuel }}</td>
                                        <td rowspan="2"> <input type="checkbox" name="checked_row[]" value="{{ $groupKey }}"> </td>
                                        {{-- Add other columns for Document Controller --}}
                                    </tr>

                                    {{-- Authenticated User's row --}}
                                    <tr>
                                        <td>{{ Auth::user()->name }}</td>
                                        <td>{{ $authUserEntry ? $authUserEntry->working_start_hour->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td>{{ $authUserEntry ? $authUserEntry->working_end_hour->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td class="{{ $workingHoursClass }}">{{ $authUserWorkingHours !== null ? number_format(abs($authUserWorkingHours), 2) . ' hrs' : 'N/A' }}</td>
                                        <td class="{{ $fuelClass }}">{{ $authUserFuel }}</td>
                                        {{-- Add other columns for Authenticated User --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @php
                            $totalAuthUserWorkingHours = $authenticatedUserTimesheets->sum('working_hours');
                            $totalDocControllerWorkingHours = $documentControllerTimesheets->sum('working_hours');
                            $totalAuthUserFuel = $authenticatedUserTimesheets->sum('fuel_consumption');
                            $totalDocControllerFuel = $documentControllerTimesheets->sum('fuel_consumption');

                            // Temporary debug output to check fuel_consumption values
                            // echo "<pre>Authenticated User Fuel Consumption Values:\n";
                            // print_r($authenticatedUserTimesheets->pluck('fuel_consumption')->toArray());
                            // echo "</pre>";
                            // echo "<pre>Document Controller Fuel Consumption Values:\n";
                            // print_r($documentControllerTimesheets->pluck('fuel_consumption')->toArray());
                            // echo "</pre>";
                        @endphp

                        <div class="mt-4">
                            <h5>Summary Totals for Date Range</h5>
                            <p><strong>{{ Auth::user()->name }} (Authenticated User):</strong> Total Working Hours: {{ number_format($totalAuthUserWorkingHours, 2) }} hrs, Total Fuel Consumption: {{ number_format($totalAuthUserFuel, 2) }}</p>
                            <p><strong>{{ $documentController->name }} (Document Controller):</strong> Total Working Hours: {{ number_format($totalDocControllerWorkingHours, 2) }} hrs, Total Fuel Consumption: {{ number_format($totalDocControllerFuel, 2) }}</p>
                        </div>

                    @endif

                    <a href="{{ route('compare.index') }}" class="btn btn-secondary mt-3">Back to Compare Form</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
