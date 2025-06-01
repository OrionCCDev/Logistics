<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectVehicle;
use App\Models\TimesheetDaily;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Vehicle;

class ProjectReports extends Component
{
    public $selectedProject = null;
    public $fromDate;
    public $toDate;
    public $projects = [];
    public $projectTableData = [];
    public $vehicleTableData = [];
    public $debugVehicles = [];
    public $debugTimesheets = [];
    public $vehicles = [];
    public $selectedVehicle = null;
    public $filterTab = 'project';
    public $users = [];
    public $selectedUser = null;
    public $userTableData = [];

    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfMonth()->toDateString();
        $this->toDate = Carbon::now()->endOfMonth()->toDateString();
        $this->projects = Project::orderBy('name')->get();
        $this->vehicles = Vehicle::orderBy('plate_number')->get();
        $this->users = \App\Models\User::where('role', 'orionDC')->orderBy('name')->get();
        $this->filterTab = 'project';
        $this->projectTableData = [];
        $this->vehicleTableData = [];
        $this->userTableData = [];
    }

    public function filter()
    {
        $this->loadTableData();
    }

    public function loadTableData()
    {
        // Validate date range
        if (!$this->fromDate || !$this->toDate) {
            $this->projectTableData = [];
            $this->vehicleTableData = [];
            $this->userTableData = [];
            return;
        }

        // Ensure full day inclusion for fromDate and toDate
        $from = Carbon::parse($this->fromDate)->startOfDay();
        $to = Carbon::parse($this->toDate)->endOfDay();

        if ($this->filterTab === 'project' && $this->selectedProject) {
            $project = Project::find($this->selectedProject);
            if (!$project) {
                $this->projectTableData = [];
                $this->vehicleTableData = [];
                $this->userTableData = [];
                return;
            }
            $timesheets = TimesheetDaily::with('vehicle')
                ->where('project_id', $project->id)
                ->whereBetween('working_start_hour', [$from, $to])
                ->where('working_hours', '>', 0)
                ->get();
            $this->projectTableData = $timesheets->map(function ($ts) use ($project) {
                return [
                    'plate_number' => $ts->vehicle ? $ts->vehicle->plate_number : '-',
                    'vehicle_type' => $ts->vehicle ? $ts->vehicle->vehicle_type : '-',
                    'project_name' => $project->name,
                    'project_code' => $project->code,
                    'vehicle_id' => $ts->vehicle ? $ts->vehicle->id : null,
                    'date' => $ts->date ? $ts->date->format('Y-m-d') : '-',
                    'working_hours' => $ts->working_hours,
                    'fuel_consumption' => $ts->fuel_consumption,
                    'id' => $ts->id,
                ];
            })->toArray();
            $this->vehicleTableData = [];
            $this->userTableData = [];
        } elseif ($this->filterTab === 'vehicle' && $this->selectedVehicle) {
            $vehicle = Vehicle::find($this->selectedVehicle);
            if (!$vehicle) {
                $this->vehicleTableData = [];
                $this->projectTableData = [];
                $this->userTableData = [];
                return;
            }
            $timesheets = TimesheetDaily::with('project')
                ->where('vehicle_id', $vehicle->id)
                ->whereBetween('working_start_hour', [$from, $to])
                ->where('working_hours', '>', 0)
                ->get();
            $this->vehicleTableData = $timesheets->map(function ($ts) use ($vehicle) {
                return [
                    'date' => $ts->date ? $ts->date->format('Y-m-d') : '-',
                    'project_name' => $ts->project ? $ts->project->name : '-',
                    'project_code' => $ts->project ? $ts->project->code : '-',
                    'working_hours' => $ts->working_hours,
                    'fuel_consumption' => $ts->fuel_consumption,
                    'id' => $ts->id,
                ];
            })->toArray();
            $this->projectTableData = [];
            $this->userTableData = [];
        } elseif ($this->filterTab === 'user' && $this->selectedUser) {
            $user = \App\Models\User::find($this->selectedUser);
            if (!$user) {
                $this->userTableData = [];
                $this->projectTableData = [];
                $this->vehicleTableData = [];
                return;
            }
            $timesheets = TimesheetDaily::with(['project', 'vehicle'])
                ->where('user_id', $user->id)
                ->whereBetween('date', [$from, $to])
                ->where('working_hours', '>', 0)
                ->get();
            $this->userTableData = $timesheets->map(function ($ts) use ($user) {
                return [
                    'date' => $ts->date ? $ts->date->format('Y-m-d') : '-',
                    'project_name' => $ts->project ? $ts->project->name : '-',
                    'project_code' => $ts->project ? $ts->project->code : '-',
                    'vehicle_plate' => $ts->vehicle ? $ts->vehicle->plate_number : '-',
                    'vehicle_type' => $ts->vehicle ? $ts->vehicle->vehicle_type : '-',
                    'working_hours' => $ts->working_hours,
                    'fuel_consumption' => $ts->fuel_consumption,
                    'id' => $ts->id,
                ];
            })->toArray();
            $this->projectTableData = [];
            $this->vehicleTableData = [];
        } else {
            $this->projectTableData = [];
            $this->vehicleTableData = [];
            $this->userTableData = [];
        }
    }

    public function render()
    {
        return view('livewire.project-reports', [
            'projects' => $this->projects,
            'vehicles' => $this->vehicles,
            'users' => $this->users,
            'selectedProject' => $this->selectedProject,
            'selectedVehicle' => $this->selectedVehicle,
            'selectedUser' => $this->selectedUser,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'projectTableData' => $this->projectTableData,
            'vehicleTableData' => $this->vehicleTableData,
            'userTableData' => $this->userTableData,
            'filterTab' => $this->filterTab,
        ]);
    }
}
