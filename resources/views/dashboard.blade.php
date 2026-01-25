@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900 font-sans">
                نظرة عامة
            </h1>
            <div class="mt-4 sm:ml-4 sm:mt-0">
                <p class="text-sm text-gray-500">آخر تحديث: {{ now()->translatedFormat('l j F Y') }}</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Passports Count -->
            <div
                class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                <dt>
                    <div class="absolute rounded-md bg-china-red/10 p-3">
                        <svg class="h-6 w-6 text-china-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <p class="mr-16 truncate text-sm font-medium text-gray-500">إجمالي الجوازات</p>
                </dt>
                <dd class="mr-16 flex items-baseline pb-1 sm:pb-2">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_passports'] }}</p>
                </dd>
            </div>

            <!-- Sales Agents -->
            <div
                class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                <dt>
                    <div class="absolute rounded-md bg-china-gold/10 p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="mr-16 truncate text-sm font-medium text-gray-500">المندوبين</p>
                </dt>
                <dd class="mr-16 flex items-baseline pb-1 sm:pb-2">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_sales'] }}</p>
                </dd>
            </div>

            <!-- Companies -->
            <div
                class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                <dt>
                    <div class="absolute rounded-md bg-blue-50 p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <p class="mr-16 truncate text-sm font-medium text-gray-500">الشركات</p>
                </dt>
                <dd class="mr-16 flex items-baseline pb-1 sm:pb-2">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_companies'] }}</p>
                </dd>
            </div>

            <!-- Total Profit (Admin/Finance Only) -->
            @if(in_array(auth()->user()->role, ['admin', 'finance']))
                <div
                    class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-china-red to-china-dark p-6 shadow-lg text-white">
                    <dt>
                        <div class="absolute rounded-md bg-white/20 p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="mr-16 truncate text-sm font-medium text-white/80">صافي الأرباح</p>
                    </dt>
                    <dd class="mr-16 flex items-baseline pb-1 sm:pb-2">
                        <p class="text-2xl font-bold text-white">${{ number_format($stats['total_profit'], 2) }}</p>
                    </dd>
                </div>
            @endif
        </div>

        <!-- Status Breakdown -->
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">حالة الجوازات</h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $statusLabels = [
                        'applied' => 'تم التقديم',
                        'rejected' => 'مرفوض',
                        'waiting_reciveing_passport' => 'بانتظار استلام الجواز',
                        'sent_to_jordan' => 'أرسلت للأردن',
                        'in_embassy' => 'في السفارة',
                        'sent_to_iraq' => 'أرسلت للعراق',
                    ];
                    $statusColors = [
                        'applied' => 'border-blue-500 text-blue-600 bg-blue-50',
                        'rejected' => 'border-red-500 text-red-600 bg-red-50',
                        'waiting_reciveing_passport' => 'border-yellow-500 text-yellow-600 bg-yellow-50',
                        'sent_to_jordan' => 'border-indigo-500 text-indigo-600 bg-indigo-50',
                        'in_embassy' => 'border-purple-500 text-purple-600 bg-purple-50',
                        'sent_to_iraq' => 'border-green-500 text-green-600 bg-green-50',
                    ];
                @endphp

                @foreach($stats['passports_by_status'] as $status)
                    <div
                        class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 border-r-4 {{ $statusColors[$status->status] ?? 'border-gray-500' }}">
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    {{ $statusLabels[$status->status] ?? $status->status }}</p>
                                <p class="text-xl font-bold text-gray-900 mt-1">{{ $status->count }}</p>
                            </div>
                            <div
                                class="h-8 w-8 rounded-full flex items-center justify-center opacity-20 {{ str_replace('border-', 'bg-', $statusColors[$status->status] ?? 'bg-gray-500') }}">
                                <span class="text-lg font-bold">#</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Company Debts (Admin Only) -->
        @if(auth()->user()->role === 'admin')
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-100">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">مديونية الشركات (غير المدفوعة)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pr-4 pl-3 text-right text-sm font-semibold text-gray-900 sm:pr-6">
                                    اسم الشركة</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">عدد الجوازات
                                    غير المدفوعة</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">المبلغ
                                    المستحق</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($stats['companies_debit'] as $company)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pr-4 pl-3 text-sm font-medium text-gray-900 sm:pr-6">
                                        {{ $company->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $company->unpaid_count }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-red-600">
                                        ${{ number_format($company->total_unpaid, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">لا توجد ديون مستحقة.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection