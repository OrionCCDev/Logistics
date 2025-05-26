<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('search') && $request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        $suppliers = $query->latest()->paginate(10)->withQueryString();
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('suppliers.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'trade_license' => 'nullable|file|max:5120',
            'vat_certificate' => 'nullable|file|max:5120',
            'statement' => 'nullable|file|max:5120',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->except(['logo', 'trade_license', 'vat_certificate', 'statement']);
        $data['code'] = 'SUP-' . strtoupper(Str::random(8));

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = uniqid('logo_') . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('dashAssets/uploads/suppliers/logos'), $logoName);
            $data['logo_path'] = 'dashAssets/uploads/suppliers/logos/' . $logoName;
        }

        if ($request->hasFile('trade_license')) {
            $tradeLicense = $request->file('trade_license');
            $tradeLicenseName = uniqid('trade_license_') . '.' . $tradeLicense->getClientOriginalExtension();
            $tradeLicense->move(public_path('dashAssets/uploads/suppliers/documents'), $tradeLicenseName);
            $data['trade_license_path'] = 'dashAssets/uploads/suppliers/documents/' . $tradeLicenseName;
        }

        if ($request->hasFile('vat_certificate')) {
            $vatCertificate = $request->file('vat_certificate');
            $vatCertificateName = uniqid('vat_certificate_') . '.' . $vatCertificate->getClientOriginalExtension();
            $vatCertificate->move(public_path('dashAssets/uploads/suppliers/documents'), $vatCertificateName);
            $data['vat_certificate_path'] = 'dashAssets/uploads/suppliers/documents/' . $vatCertificateName;
        }

        if ($request->hasFile('statement')) {
            $statement = $request->file('statement');
            $statementName = uniqid('statement_') . '.' . $statement->getClientOriginalExtension();
            $statement->move(public_path('dashAssets/uploads/suppliers/documents'), $statementName);
            $data['statement_path'] = 'dashAssets/uploads/suppliers/documents/' . $statementName;
        }

        Supplier::create($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $categories = \App\Models\Category::all();
        return view('suppliers.edit', compact('supplier', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'trade_license' => 'nullable|file|max:5120',
            'vat_certificate' => 'nullable|file|max:5120',
            'statement' => 'nullable|file|max:5120',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->except(['logo', 'trade_license', 'vat_certificate', 'statement']);

        if ($request->hasFile('logo')) {
            if ($supplier->logo_path && file_exists(public_path($supplier->logo_path))) {
                unlink(public_path($supplier->logo_path));
            }
            $logo = $request->file('logo');
            $logoName = uniqid('logo_') . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('dashAssets/uploads/suppliers/logos'), $logoName);
            $data['logo_path'] = 'dashAssets/uploads/suppliers/logos/' . $logoName;
        }

        if ($request->hasFile('trade_license')) {
            if ($supplier->trade_license_path && file_exists(public_path($supplier->trade_license_path))) {
                unlink(public_path($supplier->trade_license_path));
            }
            $tradeLicense = $request->file('trade_license');
            $tradeLicenseName = uniqid('trade_license_') . '.' . $tradeLicense->getClientOriginalExtension();
            $tradeLicense->move(public_path('dashAssets/uploads/suppliers/documents'), $tradeLicenseName);
            $data['trade_license_path'] = 'dashAssets/uploads/suppliers/documents/' . $tradeLicenseName;
        }

        if ($request->hasFile('vat_certificate')) {
            if ($supplier->vat_certificate_path && file_exists(public_path($supplier->vat_certificate_path))) {
                unlink(public_path($supplier->vat_certificate_path));
            }
            $vatCertificate = $request->file('vat_certificate');
            $vatCertificateName = uniqid('vat_certificate_') . '.' . $vatCertificate->getClientOriginalExtension();
            $vatCertificate->move(public_path('dashAssets/uploads/suppliers/documents'), $vatCertificateName);
            $data['vat_certificate_path'] = 'dashAssets/uploads/suppliers/documents/' . $vatCertificateName;
        }

        if ($request->hasFile('statement')) {
            if ($supplier->statement_path && file_exists(public_path($supplier->statement_path))) {
                unlink(public_path($supplier->statement_path));
            }
            $statement = $request->file('statement');
            $statementName = uniqid('statement_') . '.' . $statement->getClientOriginalExtension();
            $statement->move(public_path('dashAssets/uploads/suppliers/documents'), $statementName);
            $data['statement_path'] = 'dashAssets/uploads/suppliers/documents/' . $statementName;
        }

        $supplier->update($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->logo_path && file_exists(public_path($supplier->logo_path))) {
            unlink(public_path($supplier->logo_path));
        }
        if ($supplier->trade_license_path && file_exists(public_path($supplier->trade_license_path))) {
            unlink(public_path($supplier->trade_license_path));
        }
        if ($supplier->vat_certificate_path && file_exists(public_path($supplier->vat_certificate_path))) {
            unlink(public_path($supplier->vat_certificate_path));
        }
        if ($supplier->statement_path && file_exists(public_path($supplier->statement_path))) {
            unlink(public_path($supplier->statement_path));
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }

    public function getSupplierVehicles(Supplier $supplier)
    {
        $vehicles = $supplier->vehicles()->with(['projects' => function ($query) {
            $query->select('projects.id', 'projects.name'); // Select only necessary project fields
        }])->get();

        return response()->json($vehicles);
    }
}
