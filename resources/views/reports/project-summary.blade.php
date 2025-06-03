@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Filters Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Project Summary Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.project-summary') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="date_from">Date From</label>
                                <input type="date" class="form-control" name="date_from"
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="date_to">Date To</label>
                                <input type="date" class="form-control" name="date_to"
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-filter"></i> Apply Filters
                                    </button>
                                    <a href="{{ route('reports.project-summary') }}" class="btn btn-secondary">
                                        <i class="fa fa-refresh"></i
