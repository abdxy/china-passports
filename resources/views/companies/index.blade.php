@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:tracking-tight font-sans">الشركات</h2>
            <div class="mt-4 sm:ml-4 sm:mt-0 sm:flex-none">
                <a href="{{ route('companies.create') }}"
                    class="block rounded-md bg-china-red px-4 py-2 text-center text-sm font-bold text-white shadow-sm hover:bg-china-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-china-red transition-all">
                    إضافة شركة
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white p-4 shadow-sm ring-1 ring-gray-900/5 sm:rounded-lg">
            <form action="{{ route('companies.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 items-end">
                <!-- Search -->
                <div>
                   <label for="search" class="block text-xs font-medium text-gray-700 mb-1">بحث</label>
                   <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="اسم الشركة..." class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
               </div>

                <!-- City -->
                <div>
                   <label for="city" class="block text-xs font-medium text-gray-700 mb-1">المدينة</label>
                   <select name="city" id="city" class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6">
                      <option value="">الكل</option>
                      @foreach($cities as $key => $label)
                          <option value="{{ $key }}" {{ request('city') == $key ? 'selected' : '' }}>{{ $label }}</option>
                      @endforeach
                  </select>
              </div>

               <div class="flex justify-end gap-2">
                   <a href="{{ route('companies.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">إعادة تعيين</a>
                   <button type="submit" class="rounded-md bg-china-red px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-china-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-china-red transition-all">تطبيق</button>
               </div>
           </form>
       </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pr-4 pl-3 text-right text-sm font-semibold text-gray-900 sm:pr-6">
                                اسم الشركة</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">المندوب</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">الموقع</th>
                            <th scope="col" class="relative py-3.5 pr-3 pl-4 sm:pl-6">
                                <span class="sr-only">إجراءات</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($companies as $company)
                            <tr>
                                <td class="whitespace-nowrap py-4 pr-4 pl-3 text-sm font-medium text-gray-900 sm:pr-6">
                                    {{ $company->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $company->sale ? $company->sale->name : '-' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ config('iraq_cities.' . $company->city, $company->city) }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pr-3 pl-4 text-left text-sm font-medium sm:pl-6">
                                    <a href="{{ route('companies.edit', $company) }}"
                                        class="text-china-dark hover:text-china-red">تعديل</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">لا توجد شركات.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6" dir="ltr">
                {{ $companies->links() }}
            </div>
        </div>
    </div>
@endsection