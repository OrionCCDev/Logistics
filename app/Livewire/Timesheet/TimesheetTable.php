<?php

namespace App\Livewire\Timesheet;

use App\Models\TimesheetDaily;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class TimesheetTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $search = '';
    public $projectFilter = '';
    public $projects = [];
    public $thisMonthOnly = false;
    public $timesheetIdToDelete = null;
    public $page = 1;

    protected $listeners = [
        'refreshTimesheetTable' => '$refresh',
    ];

    protected $queryString = [
        'page' => ['except' => 1],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->projects = Project::orderBy('name')->get();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProjectFilter()
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
        if ($this->timesheetIdToDelete) {
            $timesheet = TimesheetDaily::find($this->timesheetIdToDelete);
            if ($timesheet) {
                try {
                    $timesheet->delete();
                    session()->flash('message', 'Timesheet entry deleted successfully.');
                } catch (\Exception $e) {
                    session()->flash('error', 'Error deleting timesheet entry: ' . $e->getMessage());
                }
            } else {
                session()->flash('error', 'Could not find timesheet entry to delete.');
            }
            $this->timesheetIdToDelete = null;
        }
    }

    public function filterThisMonth()
    {
        $this->thisMonthOnly = true;
        $this->resetPage();
    }

    public function clearMonthFilter()
    {
        $this->thisMonthOnly = false;
        $this->resetPage();
    }

    public function render()
    {
        $query = TimesheetDaily::with(['project', 'vehicle', 'user'])
            ->when($this->search, function ($q) {
                $q->where(function ($subQuery) {
                    $subQuery->where('date', 'like', '%' . $this->search . '%')
                             ->orWhereHas('project', function ($projectQuery) {
                                 $projectQuery->where('name', 'like', '%' . $this->search . '%');
                             })
                             ->orWhere('working_hours', 'like', '%' . $this->search . '%')
                             ->orWhere('status', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->projectFilter, function ($q) {
                $q->where('project_id', $this->projectFilter);
            })
            ->when($this->thisMonthOnly, function ($q) {
                $q->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]);
            });

        $timesheets = $query->orderBy($this->sortField, $this->sortDirection)
                             ->orderBy('created_at', 'desc')
                             ->paginate($this->perPage);

        return view('livewire.timesheet.timesheet-table', [
            'timesheets' => $timesheets,
            'projects' => $this->projects,
        ]);
    }
}
