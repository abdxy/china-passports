@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:tracking-tight">Passport Applications</h2>
            @if(in_array(auth()->user()->role, ['admin', 'data_entry']))
                <div class="mt-4 sm:ml-4 sm:mt-0 sm:flex-none">
                    <a href="{{ route('passports.create') }}"
                        class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Add Passport
                    </a>
                </div>
            @endif
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <form action="{{ route('passports.index') }}" method="GET"
                class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 md:grid-cols-4 sm:gap-x-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <div class="mt-1">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Name or Passport ID"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-1">
                        <select name="status" id="status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Statuses</option>
                            @foreach(['applied', 'rejected', 'waiting_reciveing_passport', 'sent_to_jordan', 'in_embassy', 'sent_to_iraq'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment</label>
                    <div class="mt-1">
                        <select name="payment_status" id="payment_status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Payments</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="not_paid" {{ request('payment_status') == 'not_paid' ? 'selected' : '' }}>Not Paid
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                Full Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Passport ID
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Company</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Price</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Payment</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($passports as $passport)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ $passport->full_name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $passport->passport_id }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $passport->company ? $passport->company->name : 'N/A' }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @php
                                        $colors = [
                                            'applied' => 'bg-blue-100 text-blue-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'waiting_reciveing_passport' => 'bg-yellow-100 text-yellow-800',
                                            'sent_to_jordan' => 'bg-indigo-100 text-indigo-800',
                                            'in_embassy' => 'bg-purple-100 text-purple-800',
                                            'sent_to_iraq' => 'bg-green-100 text-green-800',
                                        ];
                                        $colorClass = $colors[$passport->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span
                                        class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $colorClass }}">
                                        {{ ucwords(str_replace('_', ' ', $passport->status)) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    ${{ number_format($passport->price, 2) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <span
                                        class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $passport->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $passport->payment_status)) }}
                                    </span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    @if(in_array(auth()->user()->role, ['admin', 'data_entry']))
                                        <a href="{{ route('passports.edit', $passport) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                                    @endif
                                    <!-- View Link for everyone? -->
                                    {{-- <a href="{{ route('passports.show', $passport) }}"
                                        class="text-gray-600 hover:text-gray-900">View</a> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No passports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $passports->links() }}
            </div>
        </div>
    </div>
@endsection