@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight font-sans">
                    تفاصيل الجواز</h2>
            </div>
            <div class="mt-4 flex items-center gap-x-4 md:ml-4 md:mt-0">
                @if(in_array(auth()->user()->role, ['admin', 'data_entry']))
                    <a href="{{ route('passports.edit', $passport) }}"
                        class="inline-flex items-center rounded-md bg-china-red px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-china-dark">
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        تعديل
                    </a>
                @endif
                <a href="{{ route('passports.index') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-200">
                    رجوع للقائمة
                </a>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden">
            <!-- Status Banner -->
            <div class="px-6 py-4 border-b border-gray-200 
                @if($passport->status === 'rejected') bg-red-50 
                @elseif($passport->status === 'sent_to_iraq') bg-green-50 
                @else bg-yellow-50 @endif">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-3">
                        <span class="text-sm font-medium text-gray-600">حالة الجواز:</span>
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
                                'applied' => 'bg-blue-100 text-blue-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'waiting_reciveing_passport' => 'bg-yellow-100 text-yellow-800',
                                'sent_to_jordan' => 'bg-purple-100 text-purple-800',
                                'in_embassy' => 'bg-orange-100 text-orange-800',
                                'sent_to_iraq' => 'bg-green-100 text-green-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $statusColors[$passport->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$passport->status] ?? $passport->status }}
                        </span>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <span class="text-sm font-medium text-gray-600">حالة الدفع:</span>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium 
                            {{ $passport->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $passport->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-8">
                <!-- Personal Information Section -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        البيانات الشخصية
                    </h3>
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">الاسم الكامل</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $passport->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">رقم الجواز</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 font-mono" dir="ltr">{{ $passport->passport_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">هل لديه تأشيرة سابقة؟</dt>
                            <dd class="mt-1 text-lg font-semibold {{ $passport->have_china_previous_visa ? 'text-green-600' : 'text-gray-900' }}">
                                {{ $passport->have_china_previous_visa ? 'نعم' : 'لا' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">السعر</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($passport->price) }} دينار</dd>
                        </div>
                    </dl>
                </div>

                <!-- Company/Sale Section -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        جهة التقديم
                    </h3>
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">الشركة</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $passport->company->name ?? 'غير محدد' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">مندوب المبيعات</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $passport->sale->name ?? 'غير محدد' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Images Section -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        المرفقات
                    </h3>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <!-- Passport Image -->
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500 mb-2">صورة الجواز</dt>
                            @if($passport->passport_image_url)
                                <a href="{{ $passport->passport_image_url }}" target="_blank" class="block">
                                    <img src="{{ $passport->passport_image_url }}" alt="صورة الجواز" 
                                        class="h-32 w-full object-cover rounded-lg border border-gray-200 hover:opacity-75 transition-opacity">
                                </a>
                            @else
                                <div class="h-32 w-full bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">غير متوفر</span>
                                </div>
                            @endif
                        </div>

                        <!-- Personal Image -->
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500 mb-2">الصورة الشخصية</dt>
                            @if($passport->personal_image_url)
                                <a href="{{ $passport->personal_image_url }}" target="_blank" class="block">
                                    <img src="{{ $passport->personal_image_url }}" alt="الصورة الشخصية" 
                                        class="h-32 w-full object-cover rounded-lg border border-gray-200 hover:opacity-75 transition-opacity">
                                </a>
                            @else
                                <div class="h-32 w-full bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">غير متوفر</span>
                                </div>
                            @endif
                        </div>

                        <!-- Old Visa -->
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500 mb-2">التأشيرة السابقة</dt>
                            @if($passport->old_visa_url)
                                <a href="{{ $passport->old_visa_url }}" target="_blank" class="block">
                                    <img src="{{ $passport->old_visa_url }}" alt="التأشيرة السابقة" 
                                        class="h-32 w-full object-cover rounded-lg border border-gray-200 hover:opacity-75 transition-opacity">
                                </a>
                            @else
                                <div class="h-32 w-full bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">غير متوفر</span>
                                </div>
                            @endif
                        </div>

                        <!-- Empty Page Passport -->
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500 mb-2">صفحة الجواز الفارغة</dt>
                            @if($passport->empty_page_passport_url)
                                <a href="{{ $passport->empty_page_passport_url }}" target="_blank" class="block">
                                    <img src="{{ $passport->empty_page_passport_url }}" alt="صفحة الجواز الفارغة" 
                                        class="h-32 w-full object-cover rounded-lg border border-gray-200 hover:opacity-75 transition-opacity">
                                </a>
                            @else
                                <div class="h-32 w-full bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">غير متوفر</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="border-t border-gray-200 pt-4">
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2 text-sm text-gray-500">
                        <div>
                            <dt class="inline">تاريخ الإنشاء:</dt>
                            <dd class="inline mr-1">{{ $passport->created_at->format('Y-m-d H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="inline">آخر تحديث:</dt>
                            <dd class="inline mr-1">{{ $passport->updated_at->format('Y-m-d H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
