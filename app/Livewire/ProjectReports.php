<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectVehicle;
use App\Models\TimesheetDaily;
use Carbon\Carbon;

class ProjectReports extends Component
{
    public $selectedProject = null;
    public $fromDate;
    public $toDate;
    public $projects = [];
    public $tableData = [];

    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfMonth()->toDateString();
        $this->toDate = Carbon::now()->endOfMonth()->toDateString();
        $this->projects = Project::orderBy('name')->get();
        $this->tableData = [];
    }

    public function updatedSelectedProject()
    {
        $this->loadTableData();
    }

    public function updatedFromDate()
    {
        $this->loadTableData();
    }

    public function updatedToDate()
    {
        $this->loadTableData();
    }

    public function loadTableData()
    {
        if (!$this->selectedProject) {
            $this->tableData = [];
            return;
        }
        $project = Project::find($this->selectedProject);
        if (!$project) {
            $this->tableData = [];
            return;
        }
        $vehicleCount = ProjectVehicle::where('project_id', $project->id)->count();
        $workingHours = TimesheetDaily::where('project_id', $project->id)
            ->whereBetween('date', [$this->fromDate, $this->toDate])
            ->sum('working_hours');
        $this->tableData = [[
            'name' => $project->name,
            'code' => $project->code,
            'vehicle_count' => $vehicleCount,
            'working_hours' => $workingHours,
            'id' => $project->id,
        ]];
    }

    public function render()
    {
        return view('livewire.project-reports');
    }
}
