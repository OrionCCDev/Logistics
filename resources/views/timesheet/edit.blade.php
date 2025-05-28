@extends('layouts.app')

@section('page_title', 'Edit Daily Timesheet Entry')

@section('page_actions')
<a href="{{ route('timesheet.index') }}" class="btn btn-outline-primary btn-sm">
    <i class="fa fa-chevron-left"></i> Back to Timesheets
</a>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Edit Timesheet Entry for Vehicle: ') . $timesheet->vehicle->plate_number }}</div>

                <div class="card-body">
                    <form action="{{ route('timesheets.update', $timesheet->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Date --}}
                        <div class="form-group row mb-3">
                            <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>
                            <div class="col-md-6">
                                <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date', $timesheet->date ? \Carbon\Carbon::parse($timesheet->date)->format('Y-m-d') : '') }}" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Project --}}
                        <div class="form-group row mb-3">
                            <label for="project_id" class="col-md-4 col-form-label text-md-right">{{ __('Project') }}</label>
                            <div class="col-md-6">
                                <select id="project_id" class="form-control @error('project_id') is-invalid @enderror" name="project_id">
                                    <option value="">{{ __('Select Project') }}</option>
                                    @foreach($projects as $id => $name)
                                        <option value="{{ $id }}" {{ old('project_id', $timesheet->project_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Work Start Hour --}}
                        <div class="form-group row mb-3">
                            <label for="working_start_hour" class="col-md-4 col-form-label text-md-right">{{ __('Work Start Time') }}</label>
                            <div class="col-md-6">
                                <input id="working_start_hour" type="datetime-local" class="form-control @error('working_start_hour') is-invalid @enderror" name="working_start_hour" value="{{ old('working_start_hour', $timesheet->working_start_hour ? \Carbon\Carbon::parse($timesheet->working_start_hour)->format('Y-m-d\TH:i') : '') }}">
                                @error('working_start_hour')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Work End Hour --}}
                        <div class="form-group row mb-3">
                            <label for="working_end_hour" class="col-md-4 col-form-label text-md-right">{{ __('Work End Time') }}</label>
                            <div class="col-md-6">
                                <input id="working_end_hour" type="datetime-local" class="form-control @error('working_end_hour') is-invalid @enderror" name="working_end_hour" value="{{ old('working_end_hour', $timesheet->working_end_hour ? \Carbon\Carbon::parse($timesheet->working_end_hour)->format('Y-m-d\TH:i') : '') }}">
                                @error('working_end_hour')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Break Start At --}}
                        {{-- <div class="form-group row mb-3"> --}}
                        {{--     <label for="break_start_at" class="col-md-4 col-form-label text-md-right">{{ __('Break Start Time') }}</label> --}}
                        {{--     <div class="col-md-6"> --}}
                        {{--         <input id="break_start_at" type="datetime-local" class="form-control @error('break_start_at') is-invalid @enderror" name="break_start_at" value="{{ old('break_start_at', $timesheet->break_start_at ? \Carbon\Carbon::parse($timesheet->break_start_at)->format('Y-m-d\TH:i') : '') }}"> --}}
                        {{--         @error('break_start_at') --}}
                        {{--             <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> --}}
                        {{--         @enderror --}}
                        {{--     </div> --}}
                        {{-- </div> --}}

                        {{-- Break Ends At --}}
                        {{-- <div class="form-group row mb-3"> --}}
                        {{--     <label for="break_ends_at" class="col-md-4 col-form-label text-md-right">{{ __('Break End Time') }}</label> --}}
                        {{--     <div class="col-md-6"> --}}
                        {{--         <input id="break_ends_at" type="datetime-local" class="form-control @error('break_ends_at') is-invalid @enderror" name="break_ends_at" value="{{ old('break_ends_at', $timesheet->break_ends_at ? \Carbon\Carbon::parse($timesheet->break_ends_at)->format('Y-m-d\TH:i') : '') }}"> --}}
                        {{--         @error('break_ends_at') --}}
                        {{--             <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> --}}
                        {{--         @enderror --}}
                        {{--     </div> --}}
                        {{-- </div> --}}

                        {{-- Break Duration --}}
                        <div class="form-group row mb-3">
                            <label for="break_duration_hours" class="col-md-4 col-form-label text-md-right">{{ __('Break Duration (Hours)') }}</label>
                            <div class="col-md-6">
                                @php
                                    $breakHours = old('break_duration_hours');
                                    if ($breakHours === null && isset($timesheet->break_duration_minutes)) {
                                        $hours = floor($timesheet->break_duration_minutes / 60);
                                        $minutes = $timesheet->break_duration_minutes % 60;
                                        $breakHours = sprintf('%d.%02d', $hours, round($minutes / 60 * 100)); // e.g., 1.50 for 1h 30m
                                    }
                                @endphp
                                <input id="break_duration_hours" type="number" step="0.01" class="form-control @error('break_duration_hours') is-invalid @enderror" name="break_duration_hours" value="{{ $breakHours ?? '0.00' }}" placeholder="e.g., 1.5">
                                @error('break_duration_hours')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Working Hours (Consider if this should be auto-calculated or manual override) --}}
                        {{-- For now, let's assume it might be directly editable or comes from calculation server-side --}}
                        <div class="form-group row mb-3">
                            <label for="working_hours" class="col-md-4 col-form-label text-md-right">{{ __('Working Hours') }}</label>
                            <div class="col-md-6">
                                <input id="working_hours" type="number" step="0.01" class="form-control @error('working_hours') is-invalid @enderror" name="working_hours" value="{{ old('working_hours', $timesheet->working_hours) }}">
                                <small class="form-text text-muted">{{ __('Leave blank if calculated from start/end times, or fill to override.') }}</small>
                                @error('working_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Odometer Start --}}
                        <div class="form-group row mb-3">
                            <label for="odometer_start" class="col-md-4 col-form-label text-md-right">{{ __('Odometer Start') }}</label>
                            <div class="col-md-6">
                                <input id="odometer_start" type="number" step="0.01" class="form-control @error('odometer_start') is-invalid @enderror" name="odometer_start" value="{{ old('odometer_start', $timesheet->odometer_start) }}">
                                @error('odometer_start')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Odometer Ends --}}
                        <div class="form-group row mb-3">
                            <label for="odometer_ends" class="col-md-4 col-form-label text-md-right">{{ __('Odometer End') }}</label>
                            <div class="col-md-6">
                                <input id="odometer_ends" type="number" step="0.01" class="form-control @error('odometer_ends') is-invalid @enderror" name="odometer_ends" value="{{ old('odometer_ends', $timesheet->odometer_ends) }}">
                                @error('odometer_ends')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Fuel Consumption Status --}}
                        <div class="form-group row mb-3">
                            <label for="fuel_consumption_status" class="col-md-4 col-form-label text-md-right">{{ __('Fuel Consumption Calc') }}</label>
                            <div class="col-md-6">
                                <select id="fuel_consumption_status" class="form-control @error('fuel_consumption_status') is-invalid @enderror" name="fuel_consumption_status">
                                    <option value="">{{ __('Select Method') }}</option>
                                    <option value="by_hours" {{ old('fuel_consumption_status', $timesheet->fuel_consumption_status) == 'by_hours' ? 'selected' : '' }}>{{ __('By Hours') }}</option>
                                    <option value="by_odometer" {{ old('fuel_consumption_status', $timesheet->fuel_consumption_status) == 'by_odometer' ? 'selected' : '' }}>{{ __('By Odometer') }}</option>
                                </select>
                                @error('fuel_consumption_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Fuel Consumption --}}
                        <div class="form-group row mb-3">
                            <label for="fuel_consumption" class="col-md-4 col-form-label text-md-right">{{ __('Fuel Consumption') }}</label>
                            <div class="col-md-6">
                                <input id="fuel_consumption" type="number" step="0.01" class="form-control @error('fuel_consumption') is-invalid @enderror" name="fuel_consumption" value="{{ old('fuel_consumption', $timesheet->fuel_consumption) }}">
                                @error('fuel_consumption')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Deduction Amount --}}
                        <div class="form-group row mb-3">
                            <label for="deduction_amount" class="col-md-4 col-form-label text-md-right">{{ __('Deduction Amount') }}</label>
                            <div class="col-md-6">
                                <input id="deduction_amount" type="number" step="0.01" class="form-control @error('deduction_amount') is-invalid @enderror" name="deduction_amount" value="{{ old('deduction_amount', $timesheet->deduction_amount) }}">
                                @error('deduction_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="form-group row mb-3">
                            <label for="note" class="col-md-4 col-form-label text-md-right">{{ __('Notes') }}</label>
                            <div class="col-md-6">
                                <textarea id="note" class="form-control @error('note') is-invalid @enderror" name="note" rows="3">{{ old('note', $timesheet->note) }}</textarea>
                                @error('note')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Status (conditional display/edit based on permissions might be needed) --}}
                         <div class="form-group row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>
                            <div class="col-md-6">
                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" {{ Auth::user()->can('manage timesheets') ? '' : 'disabled' }}>
                                    <option value="draft" {{ old('status', $timesheet->status) == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                                    <option value="submitted" {{ old('status', $timesheet->status) == 'submitted' ? 'selected' : '' }}>{{ __('Submitted') }}</option>
                                    @can('approve timesheets') {{-- Or a more specific permission --}}
                                    <option value="approved" {{ old('status', $timesheet->status) == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                    <option value="rejected" {{ old('status', $timesheet->status) == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                    @endcan
                                </select>
                                @if(!Auth::user()->can('manage timesheets'))
                                    <input type="hidden" name="status" value="{{ $timesheet->status }}">
                                    <small class="form-text text-muted">{{ __('Status cannot be changed directly.') }}</small>
                                @endif
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Timesheet') }}
                                </button>
                                <a href="{{ $timesheet->vehicle ? route('vehicles.show', $timesheet->vehicle_id) : route('timesheets.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#project_id').select2({
            placeholder: "{{ __('Select Project') }}",
            allowClear: true
        });
    });
</script>
@endpush
