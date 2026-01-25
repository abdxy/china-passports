@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:tracking-tight font-sans">الجوازات</h2>
            <div class="mt-4 sm:ml-4 sm:mt-0 sm:flex-none">
                <a href="{{ route('passports.create') }}"
                    class="block rounded-md bg-china-red px-4 py-2 text-center text-sm font-bold text-white shadow-sm hover:bg-china-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-china-red transition-all">
                    إضافة جواز
                </a>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="bg-white p-4 shadow-sm ring-1 ring-gray-900/5 sm:rounded-lg">
            <form action="{{ route('passports.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="بحث بالاسم أو رقم الجواز..."
                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                </div>
                <div>
                    <button type="submit"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">بحث</button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pr-4 pl-3 text-right text-sm font-semibold text-gray-900 sm:pr-6">
                                الاسم الكامل</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">رقم الجواز
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">الحالة</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">
                                الشركة/المندوب</th>
                            <th scope="col" class="relative py-3.5 pr-3 pl-4 sm:pl-6">
                                <span class="sr-only">إجراءات</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
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
                                'applied' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                                'waiting_reciveing_passport' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                'sent_to_jordan' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                                'in_embassy' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                'sent_to_iraq' => 'bg-green-50 text-green-700 ring-green-600/20',
                            ];
                        @endphp

                        @forelse($passports as $passport)
                            <tr>
                                <td class="whitespace-nowrap py-4 pr-4 pl-3 text-sm font-medium text-gray-900 sm:pr-6">
                                    {{ $passport->full_name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono" dir="ltr">
                                    {{ $passport->passport_id }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusColors[$passport->status] ?? 'bg-gray-50 text-gray-600 ring-gray-500/10' }}">
                                        {{ $statusLabels[$passport->status] ?? $passport->status }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $passport->company ? $passport->company->name : ($passport->sale ? $passport->sale->name : '-') }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pr-3 pl-4 text-left text-sm font-medium sm:pl-6">
                                    <a href="{{ route('passports.edit', $passport) }}"
                                        class="text-china-dark hover:text-china-red">تعديل</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">لا توجد جوازات.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6" dir="ltr">
                {{ $passports->links() }}
            </div>
        </div>
    </div>
@endsection