<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    /**
     * Display a listing of the branches.
     */
    public function index()
    {
        $branches = Branch::with('country')->latest()->get();
        return view('branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create()
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        return view('branches.create', compact('countries'));
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'country_id' => 'required|exists:countries,id',
                'code' => 'nullable|string|max:255|unique:branches,code',
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'is_active' => 'boolean',
            ]);

            // Handle is_active checkbox
            $validated['is_active'] = $request->has('is_active');

            DB::beginTransaction();

            $branch = Branch::create($validated);

            DB::commit();

            return redirect()
                ->route('branches.index')
                ->with('success', 'Branch created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Branch validation error: ' . json_encode($e->errors()));
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Branch creation error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error creating branch: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified branch.
     */
    public function show(Branch $branch)
    {
        $branch->load('country');
        return view('branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit(Branch $branch)
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        return view('branches.edit', compact('branch', 'countries'));
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        try {
            Log::info('Branch update request data:', $request->all());

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'country_id' => 'required|exists:countries,id',
                'code' => 'nullable|string|max:255|unique:branches,code,' . $branch->id,
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'is_active' => 'nullable|boolean',
            ]);

            Log::info('Validated data:', $validated);

            DB::beginTransaction();

            // Handle is_active checkbox - explicitly set to false if not present
            $validated['is_active'] = $request->has('is_active') ? true : false;

            Log::info('Final data to update:', $validated);

            $updated = $branch->update($validated);

            Log::info('Update result:', ['success' => $updated]);

            DB::commit();

            return redirect()
                ->route('branches.index')
                ->with('toast_success', 'Branch updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Branch validation error:', ['errors' => $e->errors()]);
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('toast_error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Branch update error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()
                ->back()
                ->withInput()
                ->with('toast_error', 'Error updating branch: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified branch from storage.
     */
    public function destroy(Branch $branch)
    {
        try {
            DB::beginTransaction();

            $branch->delete();

            DB::commit();

            return redirect()
                ->route('branches.index')
                ->with('success', 'Branch deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error deleting branch: ' . $e->getMessage());
        }
    }
}
