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

        // Count for Sales Agents (needed for dashboard)
        $totalSales = Sale::count();

        // Count for Companies (needed for dashboard)
        $totalCompanies = Company::count();

        // "Companies debit to company" logic
        $companiesDebit = Company::withSum([
            'passports' => function ($query) {
                $query->where('payment_status', 'not_paid');
            }
        ], 'price')
            ->get()
            ->map(function ($company) {
                $company->total_unpaid = $company->passports_sum_price ?? 0;
                $company->unpaid_count = $company->passports()->where('payment_status', 'not_paid')->count();
                return $company;
            })
            ->filter(function ($company) {
                return $company->total_unpaid > 0;
            })
            ->values();

        // Total Debit
        $totalDebit = Passport::where('payment_status', 'not_paid')->sum('price');

        // Passports by Status
        $passportsByStatus = Passport::selectRaw('status, count(*) as count')->groupBy('status')->get();


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

        $stats = [
            'total_passports' => $totalPassports,
            'total_paid' => $totalPaid,
            'total_unpaid' => $totalUnpaid,
            'total_sales' => $totalSales,
            'total_companies' => $totalCompanies,
            'total_debit' => $totalDebit,
            'shipping_total' => $shippingTotal,
            'iraq_agent_total' => $iraqAgentTotal,
            'sales_commission_total' => $salesCommissionTotal,
            'total_profit' => $netProfitTotal,
            'companies_debit' => $companiesDebit,
            'passports_by_status' => $passportsByStatus
        ];

        return view('dashboard', compact('stats'));
    }
}
