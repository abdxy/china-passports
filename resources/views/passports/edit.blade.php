@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight font-sans">
                    تعديل بيانات الجواز</h2>
            </div>

            @if(auth()->user()->role === 'admin')
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <form action="{{ route('passports.destroy', $passport) }}" method="POST"
                        onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">حذف</button>
                    </form>
                </div>
            @endif
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            <form action="{{ route('passports.update', $passport) }}" method="POST" class="p-6 space-y-8">
                @csrf
                @method('PUT')

                <!-- Personal Information -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-2 mb-4">البيانات
                        الشخصية</h3>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="full_name" class="block text-sm font-medium leading-6 text-gray-900">الاسم
                                الكامل</label>
                            <div class="mt-2">
                                <input type="text" name="full_name" id="full_name" value="{{ $passport->full_name }}"
                                    required
                                    class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div>
                            <label for="passport_id" class="block text-sm font-medium leading-6 text-gray-900">رقم
                                الجواز</label>
                            <div class="mt-2">
                                <input type="text" name="passport_id" id="passport_id" value="{{ $passport->passport_id }}"
                                    required
                                    class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6 text-left"
                                    dir="ltr">
                            </div>
                        </div>

                        <div>
                            <label for="have_china_previous_visa"
                                class="block text-sm font-medium leading-6 text-gray-900">هل لديه تأشيرة سابقة؟</label>
                            <div class="mt-2">
                                <select id="have_china_previous_visa" name="have_china_previous_visa"
                                    class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                                    <option value="0" {{ !$passport->have_china_previous_visa ? 'selected' : '' }}>لا
                                    </option>
                                    <option value="1" {{ $passport->have_china_previous_visa ? 'selected' : '' }}>نعم
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company/Agent Selection -->
                @if(in_array(auth()->user()->role, ['admin', 'data_entry']))
                    <div>
                        <h3 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-2 mb-4">جهة
                            التقديم</h3>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                            <div>
                                <label for="sale_id" class="block text-sm font-medium leading-6 text-gray-900">مندوب
                                    المبيعات</label>
                                <div class="mt-2">
                                    <select id="sale_id" name="sale_id"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                                        <option value="">اختر المندوب</option>
                                        @foreach($sales as $sale)
                                            <option value="{{ $sale->id }}" {{ $passport->sale_id == $sale->id ? 'selected' : '' }}>
                                                {{ $sale->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="company_id" class="block text-sm font-medium leading-6 text-gray-900">الشركة</label>
                                <div class="mt-2">
                                    <select id="company_id" name="company_id"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                                        <option value="">اختر الشركة</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ $passport->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Status & Payment (Admins Only) -->
                @if(in_array(auth()->user()->role, ['admin', 'finance']))
                    <div class="bg-gray-50 p-4 rounded-lg ring-1 ring-gray-200">
                        <h3 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-2 mb-4">الحالة
                            والمدفوعات</h3>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                            <div>
                                <label for="status" class="block text-sm font-medium leading-6 text-gray-900">حالة
                                    الجواز</label>
                                <div class="mt-2">
                                    <select id="status" name="status"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                                        @foreach(['applied' => 'تم التقديم', 'rejected' => 'مرفوض', 'waiting_reciveing_passport' => 'بانتظار استلام الجواز', 'sent_to_jordan' => 'أرسلت للأردن', 'in_embassy' => 'في السفارة', 'sent_to_iraq' => 'أرسلت للعراق'] as $val => $label)
                                            <option value="{{ $val }}" {{ $passport->status == $val ? 'selected' : '' }}>{{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="payment_status" class="block text-sm font-medium leading-6 text-gray-900">حالة
                                    الدفع</label>
                                <div class="mt-2">
                                    <select id="payment_status" name="payment_status"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                                        <option value="not_paid" {{ $passport->payment_status == 'not_paid' ? 'selected' : '' }}>
                                            غير مدفوع</option>
                                        <option value="paid" {{ $passport->payment_status == 'paid' ? 'selected' : '' }}>مدفوع
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- File Uploads -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-2 mb-4">المرفقات
                    </h3>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-3">

                        <!-- Passport Image -->
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-900">صورة الجواز</label>
                            @include('passports.partials.file-upload-edit', ['name' => 'passport_image_url', 'id' => 'passport_image_url', 'currentValue' => $passport->passport_image_url, 'signedValue' => $passport->passport_image_signed_url])
                        </div>

                        <!-- Personal Image -->
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-900">الصورة الشخصية</label>
                            @include('passports.partials.file-upload-edit', ['name' => 'personal_image_url', 'id' => 'personal_image_url', 'currentValue' => $passport->personal_image_url, 'signedValue' => $passport->personal_image_signed_url])
                        </div>

                        <!-- Old Visa -->
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-900">التأشيرة السابقة</label>
                            @include('passports.partials.file-upload-edit', ['name' => 'old_visa_url', 'id' => 'old_visa_url', 'currentValue' => $passport->old_visa_url, 'signedValue' => $passport->old_visa_signed_url])
                        </div>

                        <!-- Empty Page Passport -->
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-900">صفحة الجواز الفارغة</label>
                            @include('passports.partials.file-upload-edit', ['name' => 'empty_page_passport_url', 'id' => 'empty_page_passport_url', 'currentValue' => $passport->empty_page_passport_url, 'signedValue' => $passport->empty_page_passport_signed_url])
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 pt-4">
                    <a href="{{ route('passports.index') }}"
                        class="text-sm font-semibold leading-6 text-gray-900 hover:text-china-red">إلغاء</a>
                    <button type="submit"
                        class="rounded-md bg-china-red px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-china-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-china-red transition-all">تحديث
                        البيانات</button>
                </div>
            </form>
        </div>
    </div>
@endsection