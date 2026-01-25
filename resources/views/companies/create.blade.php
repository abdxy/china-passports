@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight font-sans">
                    إضافة شركة جديدة</h2>
            </div>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            <form action="{{ route('companies.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">اسم الشركة</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" required
                            class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                    </div>
                </div>

                <!-- Sale Agent -->
                <div>
                    <label for="sale_id" class="block text-sm font-medium leading-6 text-gray-900">مندوب المبيعات</label>
                    <div class="mt-2">
                        <select id="sale_id" name="sale_id"
                            class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                            <option value="">اختر المندوب</option>
                            @foreach($sales as $sale)
                                <option value="{{ $sale->id }}">{{ $sale->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Country & City -->
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                    <div>
                        <label for="country" class="block text-sm font-medium leading-6 text-gray-900">الدولة</label>
                        <div class="mt-2">
                            <select id="country" name="country"
                                class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                                <option value="Iraq" selected>العراق</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium leading-6 text-gray-900">المدينة</label>
                        <div class="mt-2">
                            <select id="city" name="city"
                                class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                                <option value="">اختر المدينة</option>
                                @foreach(config('iraq_cities') as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 pt-4">
                    <a href="{{ route('companies.index') }}"
                        class="text-sm font-semibold leading-6 text-gray-900 hover:text-china-red">إلغاء</a>
                    <button type="submit"
                        class="rounded-md bg-china-red px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-china-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-china-red transition-all">حفظ</button>
                </div>
            </form>
        </div>
    </div>
@endsection