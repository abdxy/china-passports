<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $query = Company::query()->with('sale');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $companies = $query->latest()->paginate(20);

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }
        $sales = Sale::all();
        return view('companies.create', compact('sales'));
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sale_id' => 'required|exists:sales,id',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }
        $sales = Sale::all();
        return view('companies.edit', compact('company', 'sales'));
    }

    public function update(Request $request, Company $company)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sale_id' => 'required|exists:sales,id',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
