@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">New
                    Passport Application</h2>
            </div>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            <form action="{{ route('passports.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Company -->
                <div>
                    <label for="company_id" class="block text-sm font-medium leading-6 text-gray-900">Company</label>
                    <div class="mt-2">
                        <select id="company_id" name="company_id"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }} ({{ $company->country }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('company_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>
                    <div class="mt-2">
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                    </div>
                    @error('full_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passport ID -->
                <div>
                    <label for="passport_id" class="block text-sm font-medium leading-6 text-gray-900">Passport ID</label>
                    <div class="mt-2">
                        <input type="text" name="passport_id" id="passport_id" value="{{ old('passport_id') }}"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                    </div>
                    @error('passport_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Previous Visa Checkbox -->
                <div class="relative flex items-start">
                    <div class="flex h-6 items-center">
                        <input id="have_china_previous_visa" name="have_china_previous_visa" type="checkbox" value="1" {{ old('have_china_previous_visa') ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                    </div>
                    <div class="ml-3 text-sm leading-6">
                        <label for="have_china_previous_visa" class="font-medium text-gray-900">Has Previous China
                            Visa?</label>
                        <p class="text-gray-500">If checked, price will be 900. Otherwise 1200.</p>
                    </div>
                </div>

                <!-- File Uploads Section -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Passport Image -->
                    @include('passports.partials.file-upload', ['label' => 'Passport Image', 'name' => 'passport_image_url'])

                    <!-- Personal Image -->
                    @include('passports.partials.file-upload', ['label' => 'Personal Image', 'name' => 'personal_image_url'])

                    <!-- Old Visa Image -->
                    <div x-data="{ show: {{ old('have_china_previous_visa') ? 'true' : 'false' }} }"
                        x-init="$watch('show', value => { if(!value) $dispatch('clear-old-visa') })" class="col-span-full"
                        @change.window="if ($event.target.name === 'have_china_previous_visa') show = $event.target.checked">

                        <div x-show="show" x-transition>
                            @include('passports.partials.file-upload', ['label' => 'Old Visa Image', 'name' => 'old_visa_url'])
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="status" class="block text-sm font-medium leading-6 text-gray-900">Application
                            Status</label>
                        <div class="mt-2">
                            <select id="status" name="status"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                                @foreach(['applied', 'rejected', 'waiting_reciveing_passport', 'sent_to_jordan', 'in_embassy', 'sent_to_iraq'] as $status)
                                    <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="payment_status" class="block text-sm font-medium leading-6 text-gray-900">Payment
                            Status</label>
                        <div class="mt-2">
                            <select id="payment_status" name="payment_status"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                                <option value="not_paid" {{ old('payment_status') == 'not_paid' ? 'selected' : '' }}>Not Paid
                                </option>
                                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 pt-4">
                    <a href="{{ route('passports.index') }}"
                        class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                    <button type="submit"
                        class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Save
                        Application</button>
                </div>
            </form>
        </div>
    </div>
@endsection