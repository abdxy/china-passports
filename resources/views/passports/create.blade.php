@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight font-sans">
                    إضافة جواز جديد</h2>
            </div>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            <form action="{{ route('passports.store') }}" method="POST" class="p-6 space-y-8">
                @csrf

                <!-- Personal Information -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200 pb-2 mb-4">البيانات
                        الشخصية</h3>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="full_name" class="block text-sm font-medium leading-6 text-gray-900">الاسم
                                الكامل</label>
                            <div class="mt-2">
                                <input type="text" name="full_name" id="full_name" required
                                    class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div>
                            <label for="passport_id" class="block text-sm font-medium leading-6 text-gray-900">رقم
                                الجواز</label>
                            <div class="mt-2">
                                <input type="text" name="passport_id" id="passport_id" required
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
                                    <option value="0">لا</option>
                                    <option value="1">نعم</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company/Agent Selection (Admin/Data Entry Only) -->
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
                                        <option value="">اختر المندوب (اختياري)</option>
                                        @foreach($sales as $sale)
                                            <option value="{{ $sale->id }}">{{ $sale->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="company_id" class="block text-sm font-medium leading-6 text-gray-900">الشركة</label>
                                <div class="mt-2">
                                    <select id="company_id" name="company_id"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                                        <option value="">اختر الشركة (اختياري)</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
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
                            @include('passports.partials.file-upload', ['name' => 'passport_image_url', 'id' => 'passport_image_url'])
                        </div>

                        <!-- Personal Image -->
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-900">الصورة الشخصية</label>
                            @include('passports.partials.file-upload', ['name' => 'personal_image_url', 'id' => 'personal_image_url'])
                        </div>

                        <!-- Old Visa -->
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-900">التأشيرة السابقة (إن وجدت)</label>
                            @include('passports.partials.file-upload', ['name' => 'old_visa_url', 'id' => 'old_visa_url'])
                        </div>

                        <!-- Empty Page Passport -->
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-900">صفحة الجواز الفارغة</label>
                            @include('passports.partials.file-upload', ['name' => 'empty_page_passport_url', 'id' => 'empty_page_passport_url'])
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 pt-4">
                    <a href="{{ route('passports.index') }}"
                        class="text-sm font-semibold leading-6 text-gray-900 hover:text-china-red">إلغاء</a>
                    <button type="submit"
                        class="rounded-md bg-china-red px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-china-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-china-red transition-all">حفظ
                        البيانات</button>
                </div>
            </form>
        </div>
    </div>
@endsection