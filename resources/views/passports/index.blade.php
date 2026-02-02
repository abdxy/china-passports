@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:tracking-tight font-sans">الجوازات</h2>
            @if(in_array(auth()->user()->role, ['admin', 'data_entry']))
                <div class="mt-4 sm:ml-4 sm:mt-0 sm:flex-none">
                    <a href="{{ route('passports.create') }}"
                        class="block rounded-md bg-china-red px-4 py-2 text-center text-sm font-bold text-white shadow-sm hover:bg-china-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-china-red transition-all">
                        إضافة جواز
                    </a>
                </div>
            @endif
        </div>

        <!-- Advanced Filters -->
        <div class="bg-white p-4 shadow-sm ring-1 ring-gray-900/5 sm:rounded-lg">
            <form action="{{ route('passports.index') }}" method="GET"
                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6 items-end">

                <!-- Search -->
                <div>
                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1">بحث</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="الاسم أو رقم الجواز..."
                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">الحالة</label>
                    <select name="status" id="status"
                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                        <option value="">الكل</option>
                        @foreach(['applied' => 'تم التقديم', 'rejected' => 'مرفوض', 'waiting_reciveing_passport' => 'بانتظار استلام الجواز', 'sent_to_jordan' => 'أرسلت للأردن', 'in_embassy' => 'في السفارة', 'sent_to_iraq' => 'أرسلت للعراق'] as $val => $label)
                            <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Company -->
                <div>
                    <label for="company_id" class="block text-xs font-medium text-gray-700 mb-1">الشركة</label>
                    <select name="company_id" id="company_id"
                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                        <option value="">الكل</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sale Agent -->
                <div>
                    <label for="sale_id" class="block text-xs font-medium text-gray-700 mb-1">المندوب</label>
                    <select name="sale_id" id="sale_id"
                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                        <option value="">الكل</option>
                        @foreach($sales as $sale)
                            <option value="{{ $sale->id }}" {{ request('sale_id') == $sale->id ? 'selected' : '' }}>
                                {{ $sale->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-xs font-medium text-gray-700 mb-1">المدينة</label>
                    <select name="city" id="city"
                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                        <option value="">الكل</option>
                        @foreach($cities as $key => $label)
                            <option value="{{ $key }}" {{ request('city') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Status -->
                <div>
                    <label for="payment_status" class="block text-xs font-medium text-gray-700 mb-1">حالة الدفع</label>
                    <select name="payment_status" id="payment_status"
                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                        <option value="">الكل</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                        <option value="not_paid" {{ request('payment_status') == 'not_paid' ? 'selected' : '' }}>غير مدفوع
                        </option>
                    </select>
                </div>

                <div class="lg:col-span-6 flex justify-end gap-2 mt-2">
                    <a href="{{ route('passports.index') }}"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">إعادة
                        تعيين</a>
                    <button type="submit"
                        class="rounded-md bg-china-red px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-china-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-china-red transition-all">تطبيق
                        الفلاتر</button>
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
                            @if(in_array(auth()->user()->role, ['admin', 'finance']))
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">الدفع</th>
                            @endif
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
                                @if(in_array(auth()->user()->role, ['admin', 'finance']))
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if($passport->payment_status === 'paid')
                                            <span
                                                class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">مدفوع</span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">غير
                                                مدفوع</span>
                                        @endif
                                    </td>
                                @endif
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @if($passport->company)
                                        <span
                                            class="inline-flex items-center rounded-md bg-white px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-700/10">
                                            {{ $passport->company->name }}
                                            ({{ config('iraq_cities.' . $passport->company->city, $passport->company->city) }})
                                        </span>
                                    @elseif($passport->sale)
                                        <span
                                            class="inline-flex items-center rounded-md bg-white px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-700/10">
                                            {{ $passport->sale->name }}
                                            ({{ config('iraq_cities.' . $passport->sale->city, $passport->sale->city) }})
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="relative whitespace-nowrap py-4 pr-3 pl-4 text-left text-sm font-medium sm:pl-6 space-x-3 space-x-reverse">
                                    <a href="{{ route('passports.show', $passport) }}"
                                        class="text-blue-600 hover:text-blue-900">عرض</a>
                                    @if(in_array(auth()->user()->role, ['admin', 'data_entry']))
                                        <a href="{{ route('passports.edit', $passport) }}"
                                            class="text-china-dark hover:text-china-red">تعديل</a>
                                    @endif
                                    @if(in_array(auth()->user()->role, ['admin', 'data_entry', 'visa_applier']))
                                        <button type="button" 
                                            @click="$dispatch('open-status-modal', { id: {{ $passport->id }}, name: '{{ addslashes($passport->full_name) }}', currentStatus: '{{ $passport->status }}' })"
                                            class="text-green-600 hover:text-green-900">تغيير الحالة</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">لا توجد جوازات.</td>
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

    <!-- Status Change Modal -->
    <div x-data="{ 
            open: false, 
            passportId: null, 
            passportName: '', 
            currentStatus: '',
            loading: false,
            statusLabels: {
                'applied': 'تم التقديم',
                'rejected': 'مرفوض',
                'waiting_reciveing_passport': 'بانتظار استلام الجواز',
                'sent_to_jordan': 'أرسلت للأردن',
                'in_embassy': 'في السفارة',
                'sent_to_iraq': 'أرسلت للعراق'
            }
        }" 
        x-on:open-status-modal.window="open = true; passportId = $event.detail.id; passportName = $event.detail.name; currentStatus = $event.detail.currentStatus"
        x-show="open" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" 
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                @click.away="open = false">
                
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mr-4 sm:mt-0 sm:text-right w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">تغيير حالة الجواز</h3>
                        <p class="mt-2 text-sm text-gray-500" x-text="passportName"></p>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة الجديدة</label>
                    <select x-model="currentStatus" 
                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                        <option value="applied">تم التقديم</option>
                        <option value="rejected">مرفوض</option>
                        <option value="waiting_reciveing_passport">بانتظار استلام الجواز</option>
                        <option value="sent_to_jordan">أرسلت للأردن</option>
                        <option value="in_embassy">في السفارة</option>
                        <option value="sent_to_iraq">أرسلت للعراق</option>
                    </select>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                    <button type="button" 
                        @click="
                            loading = true;
                            fetch('/passports/' + passportId + '/status', {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ status: currentStatus })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.reload();
                                }
                            })
                            .catch(err => {
                                alert('حدث خطأ');
                                loading = false;
                            });
                        "
                        :disabled="loading"
                        class="inline-flex w-full justify-center rounded-md bg-china-red px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-china-dark sm:w-auto disabled:opacity-50">
                        <span x-show="!loading">حفظ</span>
                        <span x-show="loading">جاري الحفظ...</span>
                    </button>
                    <button type="button" @click="open = false"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">إلغاء</button>
                </div>
            </div>
        </div>
    </div>
@endsection