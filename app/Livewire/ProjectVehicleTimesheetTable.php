<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\TimesheetDaily;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ProjectVehicleTimesheetTable extends Component
{
    use WithPagination;

    public Project $project;
    public $fromDate;
    public $toDate;
    public $perPage = 15;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->fromDate = request()->query('fromDate') ?? now()->startOfMonth()->toDateString();
        $this->toDate = request()->query('toDate') ?? now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        $timesheets = TimesheetDaily::with('vehicle')
            ->where('project_id', $this->project->id)
            ->whereBetween('working_start_hour', [$this->fromDate, $this->toDate])
            ->where('working_hours', '>', 0)
            ->orderBy('working_start_hour', 'desc')
            ->paginate($this->perPage);

        return view('livewire.project-vehicle-timesheet-table', [
            'timesheets' => $timesheets,
            'project' => $this->project,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ]);
    }
}
