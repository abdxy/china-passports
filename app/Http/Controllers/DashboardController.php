<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Passport;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'visa_applier') {
            return redirect()->route('passports.index');
        }

        // Summary details
        $totalPassports = Passport::count();
        $totalPaid = Passport::where('payment_status', 'paid')->count();
        $totalUnpaid = Passport::where('payment_status', 'not_paid')->count();

        // "Companies debit to company"?
        // Assuming "unpaid passports" * price = debit?
        // Let's sum price of unpaid passports grouped by company?
        // Or "Companies debit to company" means how much Companies OWE US (the Agency).
        // Yes, unpaid passports.
        $companiesDebit = Company::withSum([
            'passports' => function ($query) {
                $query->where('payment_status', 'not_paid');
            }
        ], 'price')->get();

        // Total Debit
        $totalDebit = Passport::where('payment_status', 'not_paid')->sum('price');


        // Payment Distribution (Paid Passports)
        // 1200: 15 ship, 100 iraq, 15 sale, rest?
        // 900: 15 ship, 50 iraq, 15 sale, rest?

        // Let's calculate total profit/distribution for PAID passports.
        $paidPassports = Passport::where('payment_status', 'paid')->get();

        $shippingTotal = 0;
        $iraqAgentTotal = 0;
        $salesCommissionTotal = 0;
        $netProfitTotal = 0;

        foreach ($paidPassports as $passport) {
            $shipping = 15;
            $iraqAgent = $passport->have_china_previous_visa ? 50 : 100;
            $saleCommission = 15; // "15 for sale"

            $shippingTotal += $shipping;
            $iraqAgentTotal += $iraqAgent;
            $salesCommissionTotal += $saleCommission;

            $netProfitTotal += ($passport->price - $shipping - $iraqAgent - $saleCommission);
        }

        return view('dashboard', compact(
            'totalPassports',
            'totalPaid',
            'totalUnpaid',
            'companiesDebit',
            'totalDebit',
            'shippingTotal',
            'iraqAgentTotal',
            'salesCommissionTotal',
            'netProfitTotal'
        ));
    }
}
