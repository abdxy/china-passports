<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Passport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Passport::query()->with('company.sale');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('passport_id', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment Status Filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Role Restrictions?
        // Admin: all (done)
        // Data Entry: all (done)
        // Visa Applier: only show? "visa applier only show passworts as show only" involves read-only access.
        // If Finance: "finince on reports". Maybe they can see list too.

        $passports = $query->latest()->paginate(20)->withQueryString();

        return view('passports.index', compact('passports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Role check
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $companies = Company::all();
        return view('passports.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'full_name' => 'required|string|max:255',
            'passport_id' => 'required|string|max:255',
            'passport_image_url' => 'nullable|url',
            'personal_image_url' => 'nullable|url',
            'old_visa_url' => 'nullable|url',
            'have_china_previous_visa' => 'boolean',
            'status' => 'required|in:applied,rejected,waiting_reciveing_passport,sent_to_jordan,in_embassy,sent_to_iraq',
            'payment_status' => 'required|in:not_paid,paid',
            // Price is handled by Model or can be overridden? 
            // "price 1200 if first time, 900 if have old visa". 
            // Let's rely on model observer default, but allow override if passed?
            // User didn't ask for price override in form, but it's good practice. 
            // For now, let's rely on model logic unless we add an input for it.
        ]);

        Passport::create($validated);

        return redirect()->route('passports.index')->with('success', 'Passport application created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Passport $passport)
    {
        return view('passports.show', compact('passport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Passport $passport)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $companies = Company::all();
        return view('passports.edit', compact('passport', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Passport $passport)
    {
        if (!in_array(Auth::user()->role, ['admin', 'data_entry'])) {
            abort(403);
        }

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'full_name' => 'required|string|max:255',
            'passport_id' => 'required|string|max:255',
            'passport_image_url' => 'nullable|url',
            'personal_image_url' => 'nullable|url',
            'old_visa_url' => 'nullable|url',
            'have_china_previous_visa' => 'boolean',
            'status' => 'required|in:applied,rejected,waiting_reciveing_passport,sent_to_jordan,in_embassy,sent_to_iraq',
            'payment_status' => 'required|in:not_paid,paid',
        ]);

        if (!$request->has('have_china_previous_visa')) {
            $validated['have_china_previous_visa'] = false;
        }

        $passport->update($validated);

        return redirect()->route('passports.index')->with('success', 'Passport updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Passport $passport)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $passport->delete();

        return redirect()->route('passports.index')->with('success', 'Passport deleted successfully.');
    }
}
