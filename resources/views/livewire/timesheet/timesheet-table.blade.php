<div>
    {{-- Session Messages for Table Actions --}}
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Search and Per Page controls --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Search timesheets...">
        </div>
        <div class="col-md-3">
            <select wire:model.live="projectFilter" class="form-control">
                <option value="">All Projects</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select wire:model.live="perPage" class="form-control">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-outline-info w-100 mb-1" wire:click="filterThisMonth">
                This Month's History
            </button>
            @if($thisMonthOnly)
                <button class="btn btn-outline-secondary w-100" wire:click="clearMonthFilter">
                    Clear Filter
                </button>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="thead-light">
                <tr>
                    <th wire:click="sortBy('user_id')" style="cursor: pointer;">Created By</th>
                    <th wire:click="sortBy('date')" style="cursor: pointer;">Date</th>
                    <th wire:click="sortBy('project_id')" style="cursor: pointer;">Project</th>
                    <th wire:click="sortBy('vehicle_id')" style="cursor: pointer;">Vehicle</th>
                    <th wire:click="sortBy('working_hours')" style="cursor: pointer;">Work Hours</th>
                    <th wire:click="sortBy('fuel_consumption')" style="cursor: pointer;">Fuel Consumption</th>
                    <th wire:click="sortBy('note')" style="cursor: pointer;">Average</th>
                    <th wire:click="sortBy('note')" style="cursor: pointer;">Remarks</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($timesheets as $timesheet)
                    <tr wire:key="timesheet-row-{{ $timesheet->id }}">
                        <td>{{ $timesheet->user?->name ?? 'N/A' }}</td>
                        <td>{{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $timesheet->project?->name ?? 'N/A' }}</td>
                        <td>{{ $timesheet->vehicle?->plate_number ?? 'N/A' }}</td>
                        <td>{{ $timesheet->working_hours ?? 'N/A' }}</td>
                        <td>{{ $timesheet->fuel_consumption ?? 'N/A' }}</td>
                        <td>{{ ($timesheet->working_hours > 0) ? number_format($timesheet->fuel_consumption / $timesheet->working_hours, 2) : 'N/A' }}</td>
                        <td title="{{ $timesheet->note ?? '' }}">{{ $timesheet->note ?? 'N/A' }}</td>
                        <td>{{ $timesheet->vehicle?->supplier?->name ?? 'N/A' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('timesheet.show', $timesheet->id) }}"
                                    class="btn btn-success btn-sm" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('timesheet.edit', $timesheet->id) }}" class="btn btn-info btn-sm"
                                    title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                @if ($timesheetIdToDelete === $timesheet->id)
                                    <button class="btn btn-danger btn-sm" wire:click="deleteTimesheet">Confirm?</button>
                                    <button class="btn btn-secondary btn-sm" wire:click="cancelDelete">Cancel</button>
                                @else
                                    <button type="button" class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $timesheet->id }})" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No timesheet entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links --}}
    <div class="mt-3">
        {{ $timesheets->links('pagination::bootstrap-4') }}
    </div>
</div>
