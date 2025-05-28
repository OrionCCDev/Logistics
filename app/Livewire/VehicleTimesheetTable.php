<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\TimesheetDaily;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class VehicleTimesheetTable extends Component
{
    use WithPagination;

    public Vehicle $vehicle;
    public $perPage = 10;
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $search = '';
    public $timesheetIdToDelete = null;

    // Listener for the event from the form component
    protected $listeners = [
        'timesheetCreated' => '$refresh',
    ];

    protected $queryString = [
        'page' => ['except' => 1],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage(); // Reset to page 1 when sorting
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($timesheetId)
    {
        $this->timesheetIdToDelete = $timesheetId;
    }

    public function cancelDelete()
    {
        $this->timesheetIdToDelete = null;
    }

    public function deleteTimesheet()
    {
        Log::info('VehicleTimesheetTable: deleteTimesheet called with ID to delete: ' . $this->timesheetIdToDelete);
        if ($this->timesheetIdToDelete) {
            $timesheet = TimesheetDaily::find($this->timesheetIdToDelete);
            if ($timesheet) {
                Log::info('VehicleTimesheetTable: Found timesheet entry to delete: ', $timesheet->toArray());
                try {
                    $timesheet->delete(); // This performs the soft delete
                    Log::info('VehicleTimesheetTable: Timesheet entry soft deleted successfully.');
                    session()->flash('message', 'Timesheet entry deleted successfully.');
                } catch (\Exception $e) {
                    Log::error('VehicleTimesheetTable: Error deleting timesheet entry: ' . $e->getMessage());
                    session()->flash('error', 'Error deleting timesheet entry: ' . $e->getMessage());
                }
            } else {
                Log::warning('VehicleTimesheetTable: Could not find timesheet entry to delete with ID: ' . $this->timesheetIdToDelete);
                session()->flash('error', 'Could not find timesheet entry to delete.');
            }
            $this->timesheetIdToDelete = null;
        } else {
            Log::warning('VehicleTimesheetTable: deleteTimesheet called but timesheetIdToDelete is null.');
        }
    }

    public function render()
    {
        $query = TimesheetDaily::where('vehicle_id', $this->vehicle->id)
            ->with('project') // Eager load project relationship
            ->when($this->search, function ($q) {
                $q->where(function ($subQuery) {
                    $subQuery->where('date', 'like', '%' . $this->search . '%')
                             ->orWhereHas('project', function ($projectQuery) {
                                 $projectQuery->where('name', 'like', '%' . $this->search . '%');
                             })
                             ->orWhere('working_hours', 'like', '%' . $this->search . '%')
                             ->orWhere('status', 'like', '%' . $this->search . '%');
                });
            });

        $timesheets = $query->orderBy($this->sortField, $this->sortDirection)
                             ->paginate($this->perPage);

        return view('livewire.vehicle-timesheet-table', [
            'timesheets' => $timesheets,
        ]);
    }
}
