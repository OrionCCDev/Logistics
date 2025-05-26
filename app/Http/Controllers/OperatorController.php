<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Supplier;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operators = Operator::with(['supplier', 'vehicle'])->latest()->paginate(10);
        return view('operators.index', compact('operators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $vehicles = Vehicle::where('vehicle_status', 'active')->get();
        return view('operators.create', compact('suppliers', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {


            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'front_license_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'back_license_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'nullable|in:active,inactive',
                'license_number' => 'nullable|string|max:50',
                'license_expiry_date' => 'nullable|date',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'vehicle_id' => 'nullable|exists:vehicles,id',
            ]);



            // Set default status if not provided
            if (!isset($validated['status'])) {
                $validated['status'] = 'active';
            }

            DB::beginTransaction();


            // Handle image uploads
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('dashAssets/uploads/operators'), $imageName);
                $validated['image'] = 'dashAssets/uploads/operators/' . $imageName;
            }

            if ($request->hasFile('front_license_image')) {
                $frontLicense = $request->file('front_license_image');
                $frontLicenseName = time() . '_front_' . $frontLicense->getClientOriginalName();
                $frontLicense->move(public_path('dashAssets/uploads/operators/licenses'), $frontLicenseName);
                $validated['front_license_image'] = 'dashAssets/uploads/operators/licenses/' . $frontLicenseName;
            }

            if ($request->hasFile('back_license_image')) {
                $backLicense = $request->file('back_license_image');
                $backLicenseName = time() . '_back_' . $backLicense->getClientOriginalName();
                $backLicense->move(public_path('dashAssets/uploads/operators/licenses'), $backLicenseName);
                $validated['back_license_image'] = 'dashAssets/uploads/operators/licenses/' . $backLicenseName;
            }



            $operator = Operator::create($validated);


            DB::commit();

            return redirect()->route('operators.index')
                ->with('success', 'Operator created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Operator creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create operator. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Operator $operator)
    {
        $operator->load(['supplier', 'vehicle']);
        return view('operators.show', compact('operator'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operator $operator)
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $vehicles = Vehicle::where('vehicle_status', 'active')->get();
        return view('operators.edit', compact('operator', 'suppliers', 'vehicles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operator $operator)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'front_license_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'back_license_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'nullable|in:active,inactive',
                'license_number' => 'nullable|string|max:50',
                'license_expiry_date' => 'nullable|date',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'vehicle_id' => 'nullable|exists:vehicles,id',
            ]);

            DB::beginTransaction();

            // Handle image uploads
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($operator->image && file_exists(public_path($operator->image))) {
                    unlink(public_path($operator->image));
                }
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('dashAssets/uploads/operators'), $imageName);
                $validated['image'] = 'dashAssets/uploads/operators/' . $imageName;
            }

            if ($request->hasFile('front_license_image')) {
                if ($operator->front_license_image && file_exists(public_path($operator->front_license_image))) {
                    unlink(public_path($operator->front_license_image));
                }
                $frontLicense = $request->file('front_license_image');
                $frontLicenseName = time() . '_front_' . $frontLicense->getClientOriginalName();
                $frontLicense->move(public_path('dashAssets/uploads/operators/licenses'), $frontLicenseName);
                $validated['front_license_image'] = 'dashAssets/uploads/operators/licenses/' . $frontLicenseName;
            }

            if ($request->hasFile('back_license_image')) {
                if ($operator->back_license_image && file_exists(public_path($operator->back_license_image))) {
                    unlink(public_path($operator->back_license_image));
                }
                $backLicense = $request->file('back_license_image');
                $backLicenseName = time() . '_back_' . $backLicense->getClientOriginalName();
                $backLicense->move(public_path('dashAssets/uploads/operators/licenses'), $backLicenseName);
                $validated['back_license_image'] = 'dashAssets/uploads/operators/licenses/' . $backLicenseName;
            }

            $operator->update($validated);

            DB::commit();

            return redirect()->route('operators.index')
                ->with('success', 'Operator updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Operator update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update operator. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operator $operator)
    {
        try {
            DB::beginTransaction();

            // Delete associated images
            if ($operator->image && file_exists(public_path($operator->image))) {
                unlink(public_path($operator->image));
            }
            if ($operator->front_license_image && file_exists(public_path($operator->front_license_image))) {
                unlink(public_path($operator->front_license_image));
            }
            if ($operator->back_license_image && file_exists(public_path($operator->back_license_image))) {
                unlink(public_path($operator->back_license_image));
            }

            $operator->delete();

            DB::commit();

            return redirect()->route('operators.index')
                ->with('success', 'Operator deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Operator deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete operator. Please try again.');
        }
    }
}
