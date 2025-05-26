@extends('layouts.app')

@section('page_title', 'Edit Daily Timesheet Entry')

@section('page_actions')
<a href="{{ route('timesheet.index') }}" class="btn btn-outline-primary btn-sm">
    <i class="fa fa-chevron-left"></i> Back to Timesheets
</a>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
            @endif

            <div class="row">
                <div class="col-sm">
                    <div class="card card-sm">
                        <div class="card-header">
                            <h5 class="card-title">
                                Edit Timesheet Entry - {{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'No Date' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <livewire:timesheet.edit-form :timesheetId="$timesheet->id" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection