<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $query = Sale::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $sales = $query->latest()->paginate(20)->withQueryString();
        $cities = config('iraq_cities');

        return view('sales.index', compact('sales', 'cities'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }
        return view('sales.create');
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        Sale::create($validated);

        return redirect()->route('sales.index')->with('success', 'Sale Agent created successfully.');
    }

    public function edit(Sale $sale)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }
        return view('sales.edit', compact('sale'));
    }

    public function update(Request $request, Sale $sale)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $sale->update($validated);

        return redirect()->route('sales.index')->with('success', 'Sale Agent updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale Agent deleted successfully.');
    }
}
