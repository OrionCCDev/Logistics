<div class="row">
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Filter</h5>
                <!-- Tabs for Project/Vehicle -->
                <ul class="nav nav-tabs mb-3" id="filterTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($filterTab === 'project') active @endif" id="project-tab" type="button" wire:click="$set('filterTab', 'project')">Project</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($filterTab === 'vehicle') active @endif" id="vehicle-tab" type="button" wire:click="$set('filterTab', 'vehicle')">Vehicle</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($filterTab === 'user') active @endif" id="user-tab" type="button" wire:click="$set('filterTab', 'user')">User</button>
                    </li>
                </ul>
                <div class="row">
                    @if($filterTab === 'project')
                        <div class="col-md-6 mb-3">
                            <label for="project-select" class="form-label">Project</label>
                            <select
                                id="project-select"
                                wire:key="project-select"
                                x-data
                                x-init="
                                    const select = $el;
                                    $(select).select2({
                                        placeholder: 'Select a project',
                                        allowClear: true,
                                    });
                                    $(select).on('change', function () {
                                        window.Livewire.find(select.closest('[wire\\:id]').getAttribute('wire:id')).set('selectedProject', $(select).val());
                                    });
                                "
                                class="form-control"
                                wire:model.debounce.500ms="selectedProject"
                            >
                                <option value="">Select a project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }} ({{ $project->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="from-date" class="form-label">From</label>
                            <input type="date" id="from-date" class="form-control" wire:model.defer="fromDate">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="to-date" class="form-label">To</label>
                            <input type="date" id="to-date" class="form-control" wire:model.defer="toDate">
                        </div>
                    @elseif($filterTab === 'vehicle')
                        <div class="col-md-6 mb-3">
                            <label for="vehicle-select" class="form-label">Vehicle</label>
                            <select
                                id="vehicle-select"
                                x-data
                                x-init="
                                    const select = $el;
                                    $(select).select2({
                                        placeholder: 'Select a vehicle',
                                        allowClear: true,
                                    });
                                    $(select).on('change', function () {
                                        window.Livewire.find(select.closest('[wire\\:id]').getAttribute('wire:id')).set('selectedVehicle', $(select).val());
                                    });
                                "
                                class="form-control"
                                wire:model="selectedVehicle"
                            >
                                <option value="">Select a vehicle</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }} ({{ $vehicle->vehicle_type }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="from-date" class="form-label">From</label>
                            <input type="date" id="from-date" class="form-control" wire:model.defer="fromDate">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="to-date" class="form-label">To</label>
                            <input type="date" id="to-date" class="form-control" wire:model.defer="toDate">
                        </div>
                    @elseif($filterTab === 'user')
                        <div class="col-md-6 mb-3">
                            <label for="user-select" class="form-label">User (Document Controller)</label>
                            <select
                                id="user-select"
                                x-data
                                x-init="
                                    const select = $el;
                                    $(select).select2({
                                        placeholder: 'Select a user',
                                        allowClear: true,
                                    });
                                    $(select).on('change', function () {
                                        window.Livewire.find(select.closest('[wire\\:id]').getAttribute('wire:id')).set('selectedUser', $(select).val());
                                    });
                                "
                                class="form-control"
                                wire:model="selectedUser"
                            >
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="from-date" class="form-label">From</label>
                            <input type="date" id="from-date" class="form-control" wire:model.defer="fromDate">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="to-date" class="form-label">To</label>
                            <input type="date" id="to-date" class="form-control" wire:model.defer="toDate">
                        </div>
                    @endif
                </div>
                <button class="btn btn-primary mt-2" wire:click="filter">Filter</button>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    @if($selectedProject)
                        Project Report
                    @elseif($selectedVehicle)
                        Vehicle Timesheet Report
                    @elseif($selectedUser)
                        User Timesheet Report
                    @else
                        Project Report
                    @endif
                </h5>
                <div class="table-responsive">
                    @if($filterTab === 'project' && $selectedProject)
                        {{-- Project Tab Table --}}
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Plate Number</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Total Working Hours</th>
                                    <th>Total Fuel Consumption</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projectTableData as $row)
                                    <tr>
                                        <td>{{ $row['plate_number'] }}</td>
                                        <td>{{ $row['vehicle_type'] }}</td>
                                        <td>{{ $row['date'] }}</td>
                                        <td>{{ number_format($row['working_hours'], 2) }}</td>
                                        <td>{{ number_format($row['fuel_consumption'], 2) }}</td>
                                        <td>
                                            <a href="{{ route('projects.timesheets', ['project' => $row['project_code'], 'fromDate' => $fromDate, 'toDate' => $toDate, 'vehicle' => $row['vehicle_id']]) }}" class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No data to display.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($projectTableData) > 0)
                            <tfoot>
                                <tr class="bg-light fw-bold">
                                    <th colspan="3" class="text-end">Total</th>
                                    <th>
                                        {{ number_format(collect($projectTableData)->sum('working_hours'), 2) }}
                                    </th>
                                    <th>
                                        {{ number_format(collect($projectTableData)->sum('fuel_consumption'), 2) }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    @elseif($filterTab === 'vehicle' && $selectedVehicle)
                        {{-- Vehicle Tab Table --}}
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Project</th>
                                    <th>Total Working Hours</th>
                                    <th>Total Fuel Consumption</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehicleTableData as $row)
                                    <tr>
                                        <td>{{ $row['date'] }}</td>
                                        <td>{{ $row['project_name'] }}</td>
                                        <td>{{ number_format($row['working_hours'], 2) }}</td>
                                        <td>{{ number_format($row['fuel_consumption'], 2) }}</td>
                                        <td>
                                            <a href="{{ route('projects.timesheets', ['project' => $row['project_code'], 'fromDate' => $fromDate, 'toDate' => $toDate, 'vehicle' => $selectedVehicle]) }}" class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No data to display.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($vehicleTableData) > 0)
                            <tfoot>
                                <tr class="bg-light fw-bold">
                                    <th colspan="2" class="text-end">Total</th>
                                    <th>
                                        {{ number_format(collect($vehicleTableData)->sum('working_hours'), 2) }}
                                    </th>
                                    <th>
                                        {{ number_format(collect($vehicleTableData)->sum('fuel_consumption'), 2) }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    @elseif($filterTab === 'user' && $selectedUser)
                        {{-- User Tab Table --}}
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Project</th>
                                    <th>Vehicle</th>
                                    <th>Type</th>
                                    <th>Total Working Hours</th>
                                    <th>Total Fuel Consumption</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userTableData as $row)
                                    <tr>
                                        <td>{{ $row['date'] }}</td>
                                        <td>{{ $row['project_name'] }}</td>
                                        <td>{{ $row['vehicle_plate'] }}</td>
                                        <td>{{ $row['vehicle_type'] }}</td>
                                        <td>{{ number_format($row['working_hours'], 2) }}</td>
                                        <td>{{ number_format($row['fuel_consumption'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No data to display.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($userTableData) > 0)
                            <tfoot>
                                <tr class="bg-light fw-bold">
                                    <th colspan="4" class="text-end">Total</th>
                                    <th>
                                        {{ number_format(collect($userTableData)->sum('working_hours'), 2) }}
                                    </th>
                                    <th>
                                        {{ number_format(collect($userTableData)->sum('fuel_consumption'), 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    @else
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="text-center">No data to display.</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function initProjectSelect2() {
        const $select = $('#project-select');
        if ($select.length) {
            $select.select2({
                placeholder: 'Select a project',
                allowClear: true,
            });
            $select.off('change.select2').on('change.select2', function () {
                let wireId = $select.closest('[wire\\:id]').attr('wire:id');
                if (window.Livewire && wireId) {
                    window.Livewire.find(wireId).set('selectedProject', $select.val());
                }
            });
        }
    }
    function initVehicleSelect2() {
        const $select = $('#vehicle-select');
        if ($select.length) {
            $select.select2({
                placeholder: 'Select a vehicle',
                allowClear: true,
            });
            $select.off('change.select2').on('change.select2', function () {
                let wireId = $select.closest('[wire\\:id]').attr('wire:id');
                if (window.Livewire && wireId) {
                    window.Livewire.find(wireId).set('selectedVehicle', $select.val());
                }
            });
        }
    }
    function initUserSelect2() {
        const $select = $('#user-select');
        if ($select.length) {
            $select.select2({
                placeholder: 'Select a user',
                allowClear: true,
            });
            $select.off('change.select2').on('change.select2', function () {
                let wireId = $select.closest('[wire\\:id]').attr('wire:id');
                if (window.Livewire && wireId) {
                    window.Livewire.find(wireId).set('selectedUser', $select.val());
                }
            });
        }
    }
    document.addEventListener('livewire:load', function () {
        initProjectSelect2();
        initVehicleSelect2();
        initUserSelect2();
        window.livewire.hook('message.processed', (message, component) => {
            initProjectSelect2();
            initVehicleSelect2();
            initUserSelect2();
        });
    });
</script>
@endpush
