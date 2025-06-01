@extends('layouts.app')

@section('page_title', 'Create Daily Timesheet Entry')

@section('page_actions')
<a href="{{ route('timesheet.index') }}" class="btn btn-outline-primary btn-sm">
    <i class="fa fa-chevron-left"></i> Back to Timesheets
</a>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <h5 class="hk-sec-title">New Timesheet Entry Details</h5>
            <p class="mb-25">Fill in the details below to create a new daily timesheet entry.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <p><strong>Please correct the errors below:</strong></p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('timesheet.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- User (Driver/Operator) -->
                    <div class="col-md-6 form-group">
                        <label for="user_id">User (Operator/Driver)</label>
                        <select class="form-control select2 @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                            <option value="">Select User (Default: You)</option>
                            @foreach($users as $id => $name)
                                <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div class="col-md-6 form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Project -->
                    <div class="col-md-6 form-group">
                        <label for="project_id">Project</label>
                        <select class="form-control select2 @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                            <option value="">Select Project</option>
                            @foreach($projects as $id => $projectName)
                                <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $projectName }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Vehicle -->
                    <div class="col-md-6 form-group">
                        <label for="vehicle_id">Vehicle</label>
                        <select class="form-control select2 @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id" required>
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $id => $plateNumber)
                                <option value="{{ $id }}" {{ old('vehicle_id') == $id ? 'selected' : '' }}>{{ $plateNumber }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h6 class="mt-4">Working Hours & Breaks</h6>
                <hr>
                <div class="row">
                    <!-- Working Start Hour -->
                    <div class="col-md-4 form-group">
                        <label for="working_start_hour">Work Start Time</label>
                        <input type="time" class="form-control @error('working_start_hour') is-invalid @enderror" id="working_start_hour" name="working_start_hour" value="{{ old('working_start_hour', '06:00') }}">
                        @error('working_start_hour')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Working End Hour -->
                    <div class="col-md-4 form-group">
                        <label for="working_end_hour">Work End Time</label>
                        <input type="time" class="form-control @error('working_end_hour') is-invalid @enderror" id="working_end_hour" name="working_end_hour" value="{{ old('working_end_hour') }}">
                        @error('working_end_hour')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Break Duration -->
                    <div class="col-md-4 form-group">
                        <label for="break_duration_hours">Break Duration (Hours)</label>
                        <input type="number" step="0.01" class="form-control @error('break_duration_hours') is-invalid @enderror" id="break_duration_hours" name="break_duration_hours" value="{{ old('break_duration_hours', '1.0') }}" placeholder="e.g., 1.5">
                        @error('break_duration_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                 <!-- Working Hours (Calculated or Manual) -->
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="working_hours">Total Working Hours</label>
                        <input type="number" step="0.01" class="form-control @error('working_hours') is-invalid @enderror" id="working_hours" name="working_hours" value="{{ old('working_hours') }}" placeholder="e.g., 8.50">
                         <small class="form-text text-muted">Calculated automatically if start/end times are set, or can be entered manually.</small>
                        @error('working_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <h6 class="mt-4">Vehicle & Fuel Details</h6>
                <hr>
                <div class="row">
                    <!-- Odometer Start -->
                    <div class="col-md-4 form-group">
                        <label for="odometer_start">Odometer Start (km)</label>
                        <input type="number" step="0.01" class="form-control @error('odometer_start') is-invalid @enderror" id="odometer_start" name="odometer_start" value="{{ old('odometer_start') }}" placeholder="e.g., 12345.67">
                        @error('odometer_start')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Odometer Ends -->
                    <div class="col-md-4 form-group">
                        <label for="odometer_ends">Odometer End (km)</label>
                        <input type="number" step="0.01" class="form-control @error('odometer_ends') is-invalid @enderror" id="odometer_ends" name="odometer_ends" value="{{ old('odometer_ends') }}" placeholder="e.g., 12400.00">
                        @error('odometer_ends')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                     <!-- Fuel Consumption Status -->
                    <div class="col-md-4 form-group">
                        <label for="fuel_consumption_status">Fuel Consumption Calculated By</label>
                        <select class="form-control @error('fuel_consumption_status') is-invalid @enderror" id="fuel_consumption_status" name="fuel_consumption_status">
                            <option value="by_odometer" {{ old('fuel_consumption_status', 'by_odometer') == 'by_odometer' ? 'selected' : '' }}>By Odometer</option>
                            <option value="by_hours" {{ old('fuel_consumption_status') == 'by_hours' ? 'selected' : '' }}>By Hours</option>
                        </select>
                        @error('fuel_consumption_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Fuel Consumption -->
                    <div class="col-md-4 form-group">
                        <label for="fuel_consumption">Fuel Consumption (Liters/Gallons)</label>
                        <input type="number" step="0.01" class="form-control @error('fuel_consumption') is-invalid @enderror" id="fuel_consumption" name="fuel_consumption" value="{{ old('fuel_consumption') }}" placeholder="e.g., 50.25">
                        @error('fuel_consumption')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <h6 class="mt-4">Financials & Notes</h6>
                <hr>
                <div class="row">
                    <!-- Deduction Amount -->
                    <div class="col-md-4 form-group">
                        <label for="deduction_amount">Deduction Amount ({{-- Currency Symbol --}})</label>
                        <input type="number" step="0.01" class="form-control @error('deduction_amount') is-invalid @enderror" id="deduction_amount" name="deduction_amount" value="{{ old('deduction_amount', 0) }}" placeholder="e.g., 10.50">
                        @error('deduction_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status (Might be admin-only or based on workflow) -->
                     @if(auth()->user()->can('manage timesheets')) <!-- Example: Check if user has permission -->
                        <div class="col-md-4 form-group">
                            <label for="status">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                         <input type="hidden" name="status" value="draft">
                    @endif
                </div>

                <div class="row">
                    <!-- Note -->
                    <div class="col-md-12 form-group">
                        <label for="note">Notes / Remarks</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="4" placeholder="Any additional notes or remarks for this entry...">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Create Timesheet Entry
                    </button>
                    <a href="{{ route('timesheet.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: $(this).data('placeholder') || "Select an option",
            allowClear: true
        });

        // Basic auto-calculation for working hours (can be made more robust)
        function calculateWorkingHours() {
            let startTime = $('#working_start_hour').val();
            let endTime = $('#working_end_hour').val();
            let breakDurationHours = parseFloat($('#break_duration_hours').val());

            if (startTime && endTime) {
                let start = new Date(`1970-01-01T${startTime}:00`);
                let end = new Date(`1970-01-01T${endTime}:00`);

                let diffMs = end - start;
                if (diffMs < 0) { // Handle overnight case or error
                     $('#working_hours').val(''); // Or show error
                    return;
                }

                let workHours = diffMs / (1000 * 60 * 60);

                if (!isNaN(breakDurationHours) && breakDurationHours > 0) {
                    workHours -= breakDurationHours;
                }

                $('#working_hours').val(workHours > 0 ? workHours.toFixed(2) : '0.00');
            } else {
                // If one is cleared, clear the calculation or leave manual entry
                // $('#working_hours').val('');
            }
        }

        $('#working_start_hour, #working_end_hour, #break_duration_hours').on('change', calculateWorkingHours);

        // Auto-calculation for Odometer driven KM
        function calculateOdometerKm(){
            let odometerStart = parseFloat($('#odometer_start').val());
            let odometerEnd = parseFloat($('#odometer_ends').val());

            if (!isNaN(odometerStart) && !isNaN(odometerEnd) && odometerEnd >= odometerStart) {
                // let distance = odometerEnd - odometerStart;
                // Potentially display this somewhere or use for fuel calculation if needed
            }
        }
        $('#odometer_start, #odometer_ends').on('change', calculateOdometerKm);


    });
</script>
@endpush
