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
        <div class="col-md-2">
            <select wire:model.live="perPage" class="form-control">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    <th wire:click="sortBy('date')" style="cursor: pointer;">
                        Date @include('partials._sort-icon', ['field' => 'date'])
                    </th>
                    <th wire:click="sortBy('project_id')" style="cursor: pointer;">
                        Project @include('partials._sort-icon', ['field' => 'project_id'])
                    </th>
                    <th wire:click="sortBy('working_hours')" style="cursor: pointer;">
                        Work Hours @include('partials._sort-icon', ['field' => 'working_hours'])
                    </th>
                    {{-- Placeholder for Break Duration --}}
                    <th>Break Hours</th>
                    {{-- Placeholder for Net Hours --}}
                    <th>Net Hours</th>
                    <th wire:click="sortBy('status')" style="cursor: pointer;">
                        Status @include('partials._sort-icon', ['field' => 'status'])
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($timesheets as $timesheet)
                    <tr wire:key="timesheet-row-{{ $timesheet->id }}">
                        <td>{{ $timesheet->date->format('d-M-Y') }}</td>
                        <td>{{ $timesheet->project->name ?? 'N/A' }}</td>
                        <td>{{ $timesheet->working_hours }}</td>
                         {{-- Display calculated break duration if available --}}
                        <td>{{ $timesheet->break_duration_display ?? '0.00' }}</td>
                        {{-- Display calculated net working hours if available --}}
                        <td>{{ $timesheet->net_working_hours_display ?? $timesheet->working_hours }}</td>
                        <td><span class="badge badge-{{ $timesheet->status_color ?? 'secondary' }}">{{ Str::title($timesheet->status) }}</span></td>
                        <td>
                            <a href="{{ route('timesheets.edit', $timesheet->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            @if ($timesheetIdToDelete === $timesheet->id)
                                <button class="btn btn-sm btn-danger" wire:click="deleteTimesheet">Confirm?</button>
                                <button class="btn btn-sm btn-secondary" wire:click="cancelDelete">Cancel</button>
                            @else
                                <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $timesheet->id }})" title="Delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No timesheet entries found.</td> {{-- Adjusted colspan --}}
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links --}}
    <div class="mt-3">
        {{ $timesheets->links() }}
    </div>

    {{-- Edit Timesheet Modal Removed --}}

    @if(true)
    {{-- Test block --}}
    @endif
</div>

{{-- @push('scripts')
// ... (commented out script content) ...
@endpush --}}
