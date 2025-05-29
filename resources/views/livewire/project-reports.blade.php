<div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Filter</h5>
                <!-- Project Select -->
                <div class="mb-3">
                    <label for="project-select" class="form-label">Project</label>
                    <select id="project-select" class="form-control" wire:model="selectedProject">
                        <option value="">Select a project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }} ({{ $project->code }})</option>
                        @endforeach
                    </select>
                </div>
                <!-- Date Range -->
                <div class="mb-3">
                    <label for="from-date" class="form-label">From</label>
                    <input type="date" id="from-date" class="form-control" wire:model="fromDate">
                </div>
                <div class="mb-3">
                    <label for="to-date" class="form-label">To</label>
                    <input type="date" id="to-date" class="form-control" wire:model="toDate">
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Project Report</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Code</th>
                                <th>Number of Vehicles</th>
                                <th>Total Working Hours</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tableData as $row)
                                <tr>
                                    <td>{{ $row['name'] }}</td>
                                    <td>{{ $row['code'] }}</td>
                                    <td>{{ $row['vehicle_count'] }}</td>
                                    <td>{{ number_format($row['working_hours'], 2) }}</td>
                                    <td>
                                        <!-- Example Option: View -->
                                        <a href="#" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No data to display.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        // Initialize Select2
        $('#project-select').select2({
            placeholder: 'Select a project',
            allowClear: true,
        });
        // Sync Select2 with Livewire
        $('#project-select').on('change', function (e) {
            @this.set('selectedProject', $(this).val());
        });
        // Update Select2 when Livewire updates
        Livewire.hook('message.processed', (message, component) => {
            $('#project-select').select2();
        });
    });
</script>
@endpush
