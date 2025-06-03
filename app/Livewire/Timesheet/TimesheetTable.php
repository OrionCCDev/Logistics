<?php

namespace App\Livewire\Timesheet;

use App\Models\TimesheetDaily;
use App\Models\Project;
use Livewire\Component;

class TimesheetTable extends Component
{
    public $timesheetIdToDelete;
    public $projects;

    public function mount()
    {
        $this->projects = Project::orderBy('name')->get();
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
        if ($this->timesheetIdToDelete) {
            $timesheet = TimesheetDaily::find($this->timesheetIdToDelete);
            if ($timesheet) {
                $timesheet->delete();
                session()->flash('message', 'Timesheet deleted successfully.');
            }
            $this->timesheetIdToDelete = null;
        }
    }

    public function render()
    {
        // Get ALL timesheets with relationships
        $timesheets = TimesheetDaily::with(['project', 'vehicle.supplier', 'user'])
            ->orderBy('date', 'desc')
            ->get(); // Remove any pagination or limits

        return view('livewire.timesheet.timesheet-table', [
            'timesheets' => $timesheets
        ]);
    }
}
