@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard Overview</h2>
            <div class="text-sm text-gray-500">Welcome back, <span
                    class="font-semibold text-gray-900">{{ auth()->user()->name }}</span></div>
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'finance']))
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Passports -->
                <div
                    class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Passports</dt>
                                    <dd class="mt-1 text-2xl font-bold tracking-tight text-gray-900">{{ $totalPassports }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paid -->
                <div
                    class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="bg-green-50 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Paid Applications</dt>
                                    <dd class="mt-1 text-2xl font-bold tracking-tight text-green-600">{{ $totalPaid }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unpaid -->
                <div
                    class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="bg-red-50 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Unpaid Applications</dt>
                                    <dd class="mt-1 text-2xl font-bold tracking-tight text-red-600">{{ $totalUnpaid }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Debit -->
                <div
                    class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="bg-amber-50 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Debit (Unpaid)</dt>
                                    <dd class="mt-1 text-2xl font-bold tracking-tight text-amber-600">
                                        ${{ number_format($totalDebit, 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Companies Debit Table -->
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Company Debits (Unpaid Passports)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Company</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Country
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Total
                                        Debit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($companiesDebit as $company)
                                    @if($company->passports_sum_price > 0)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-gray-900">
                                                {{ $company->name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $company->country }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-bold text-red-600">
                                                ${{ number_format($company->passports_sum_price, 2) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @if($companiesDebit->sum('passports_sum_price') == 0)
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No outstanding debits.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Distribution -->
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Payment Distribution (Paid Only)</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Shipping Costs</span>
                                <span class="text-sm font-bold text-gray-900">${{ number_format($shippingTotal, 2) }}</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Iraq Agent Fees</span>
                                <span class="text-sm font-bold text-gray-900">${{ number_format($iraqAgentTotal, 2) }}</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Sales Commissions</span>
                                <span
                                    class="text-sm font-bold text-gray-900">${{ number_format($salesCommissionTotal, 2) }}</span>
                            </li>
                            <li class="border-t border-gray-200 pt-4 flex items-center justify-between">
                                <span class="text-base font-bold text-gray-900">Net Profit</span>
                                <span class="text-base font-bold text-green-600">${{ number_format($netProfitTotal, 2) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                <h3 class="text-lg font-semibold text-gray-900">Welcome, {{ auth()->user()->name }}</h3>
                <p class="mt-2 text-gray-600">Use the navigation menu to manage passports.</p>
            </div>
        @endif
    </div>
@endsection