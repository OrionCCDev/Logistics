<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();

        if ($currentUser->role == 'orionAdmin') {
            $users = User::with(relations: ['employee.projects'])->get();
        } elseif ($currentUser->role =='orionManager') {
            $users = User::with(['employee.projects'])
                ->whereDoesntHave('roles', function($query) {
                    $query->whereIn('name', ['orionAdmin', 'orionManager']);
                })
                ->get();
        } else {
            $users = collect(); // Return empty collection for other roles
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        $projects = Project::with('branch')->get();

        // Debug information
        Log::info('Projects count: ' . $projects->count());
        Log::info('Projects data:', $projects->toArray());

        return view('users.create', compact('branches', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:orionAdmin,orionManager,orionDC,orionUser',
            'emp_code' => 'nullable|string|max:50|unique:employees',
            'mobile' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'branch_id' => 'nullable|exists:branches,id',
            'projects' => 'nullable|array',
            'projects.*' => 'exists:projects,id',
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Handle profile image upload
            $imagePath = null;
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('dashAssets/uploads/users'), $imageName);
                $imagePath = 'dashAssets/uploads/users/' . $imageName;
            }

            // Create employee record
            $employee = Employee::create([
                'emp_code' => $request->emp_code,
                'mobile' => $request->mobile,
                'image' => $imagePath,
                'user_id' => $user->id,
                'branch_id' => $request->branch_id,
                'is_active' => $request->is_active,
            ]);

            // Assign projects if any
            if ($request->has('projects')) {
                $employee->projects()->attach($request->projects);
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating user: ' . $e->getMessage())->withInput();
        }
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $branches = Branch::where('is_active', true)->get();
        $projects = Project::with('branch')->get();
        return view('users.edit', compact('user', 'branches', 'projects'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:orionAdmin,orionManager,orionDC,orionUser',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'password' => 'nullable|string|min:8',
            'emp_code' => 'nullable|string|max:50|unique:employees,emp_code,' . ($user->employee ? $user->employee->id : 'NULL') . ',id',
            'mobile' => 'nullable|string|max:20',
            'branch_id' => 'nullable|exists:branches,id',
            'projects' => 'nullable|array',
            'projects.*' => 'exists:projects,id',
        ]);

        try {
            DB::beginTransaction();

            // Update user
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('dashAssets/uploads/users'), $imageName);

                // Update employee image
                if ($user->employee) {
                    $user->employee->update([
                        'image' => 'dashAssets/uploads/users/' . $imageName,
                        'is_active' => $request->is_active,
                        'emp_code' => $request->emp_code,
                        'mobile' => $request->mobile,
                        'branch_id' => $request->branch_id
                    ]);
                } else {
                    // Create employee record if it doesn't exist
                    Employee::create([
                        'emp_code' => $request->emp_code,
                        'mobile' => $request->mobile,
                        'image' => 'dashAssets/uploads/users/' . $imageName,
                        'user_id' => $user->id,
                        'branch_id' => $request->branch_id,
                        'is_active' => $request->is_active,
                    ]);
                }
            } else if ($user->employee) {
                // Update employee data without image
                $user->employee->update([
                    'is_active' => $request->is_active,
                    'emp_code' => $request->emp_code,
                    'mobile' => $request->mobile,
                    'branch_id' => $request->branch_id
                ]);
            } else {
                // Create employee record if it doesn't exist
                Employee::create([
                    'emp_code' => $request->emp_code,
                    'mobile' => $request->mobile,
                    'user_id' => $user->id,
                    'branch_id' => $request->branch_id,
                    'is_active' => $request->is_active,
                ]);
            }

            // Update project assignments
            if ($user->employee) {
                $user->employee->projects()->sync($request->projects ?? []);
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    /**
     * Reset user's password to default
     */
    public function resetPassword(User $user)
    {
        try {
            $user->update([
                'password' => Hash::make('Orion@123')
            ]);

            return redirect()->route('users.show', $user->id)
                ->with('success', 'Password has been reset successfully to: Orion@123');
        } catch (\Exception $e) {
            return back()->with('error', 'Error resetting password: ' . $e->getMessage());
        }
    }
}
