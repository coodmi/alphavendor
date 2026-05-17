@extends('layouts.dashboard')
@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <a href="{{ route('admin.employees') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
        <i class="fas fa-arrow-left"></i> Back to Employees
    </a>

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
        <p class="font-semibold mb-2 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Please fix the errors:</p>
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Password notice --}}
    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
        <i class="fas fa-lock text-amber-500 text-lg mt-0.5"></i>
        <div>
            <p class="font-semibold text-amber-800">Password cannot be changed here</p>
            <p class="text-sm text-amber-700 mt-0.5">Only the employee can change their own password from their profile settings.</p>
        </div>
    </div>

    <form action="{{ route('admin.employees.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fas fa-user-circle text-blue-500"></i> Basic Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title / Designation</label>
                    <input type="text" name="employee_title" value="{{ old('employee_title', $user->employee_title) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="e.g. Manager, Supervisor, Support, Accounts">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Account Status</label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="active" {{ old('status', $user->status) === 'active' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700 text-sm">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="inactive" {{ old('status', $user->status) === 'inactive' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700 text-sm">Inactive</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Photo & NID --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fas fa-id-card text-green-500"></i> Photo & NID Card
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Own Photo</label>
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-20 h-20 rounded-xl object-cover mb-2 border border-gray-200">
                    @endif
                    <input type="file" name="profile_image" accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm">
                    <p class="text-xs text-gray-400 mt-1">Leave blank to keep current photo</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">NID Card (Photo)</label>
                    @if($user->nid_card)
                        <img src="{{ asset('storage/' . $user->nid_card) }}" class="w-32 h-20 rounded-xl object-cover mb-2 border border-gray-200">
                    @endif
                    <input type="file" name="nid_card" accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm">
                    <p class="text-xs text-gray-400 mt-1">Leave blank to keep current NID</p>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.employee-permissions.edit', $user) }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-purple-100 text-purple-700 rounded-xl hover:bg-purple-200 transition-colors font-medium">
                <i class="fas fa-shield-alt"></i> Manage Permissions
            </a>
            <div class="flex gap-4">
                <a href="{{ route('admin.employees') }}"
                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold shadow-lg flex items-center gap-2">
                    <i class="fas fa-save"></i> Update Employee
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
