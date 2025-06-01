<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\TimesheetDaily;
use App\Models\Employee;

class CompareController extends Controller
{
    public function __construct()
    {
        // Middleware is applied in the routes file (web.php)
        // $this->middleware('auth');
        // $this->middleware('role:orionAdmin,orionManager');
    }

    /**
     * Display the compare form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::whereIn('role', ['documentController', 'orionDC'])->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('compare.index', compact('users', 'projects'));
    }

    /**
     * Show the comparison results.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showResults(Request $request)
    {
        $request->validate([
            'document_controller_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $authenticatedUserId = Auth::id();
        $documentControllerId = $request->input('document_controller_id');

        $documentControllerUser = User::with('employee.projects')->find($documentControllerId);

        if (!$documentControllerUser || !$documentControllerUser->employee || $documentControllerUser->employee->projects->isEmpty()) {
            return redirect()->back()->withErrors(['document_controller_id' => 'Selected user does not have a project associated.']);
        }

        $projectId = $documentControllerUser->employee->projects->first()->id;
        $project = $documentControllerUser->employee->projects->first();

        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        $authenticatedUserTimesheets = TimesheetDaily::with('vehicle')
            ->where('user_id', $authenticatedUserId)
            ->where('project_id', $projectId)
            ->whereDate('date', '>=', $startDate->toDateString())
            ->whereDate('date', '<=', $endDate->toDateString())
            ->orderBy('date')
            ->get();

        $documentControllerTimesheets = TimesheetDaily::with('vehicle')
            ->where('user_id', $documentControllerId)
            ->where('project_id', $projectId)
            ->whereDate('date', '>=', $startDate->toDateString())
            ->whereDate('date', '<=', $endDate->toDateString())
            ->orderBy('date')
            ->get();

        $documentController = $documentControllerUser;

        return view('compare.results', compact(
            'authenticatedUserTimesheets',
            'documentControllerTimesheets',
            'startDate',
            'endDate',
            'documentController',
            'project'
        ));
    }
}
