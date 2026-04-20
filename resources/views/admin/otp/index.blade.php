@extends('layouts.dashboard')

@section('title', 'OTP Management')
@section('page-title', 'OTP Management')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">OTP Logs</h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th>ID</th>
                        <th>Phone Number</th>
                        <th>OTP</th>
                        <th>Status</th>
                        <th>Purpose</th>
                        <th>Created At</th>
                        <th>Expires At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($otps as $otp)
                    <tr>
                        <td>{{ $otp->id }}</td>
                        <td>{{ $otp->phone_number }}</td>
                        <td>{{ $otp->otp_code }}</td>
                        <td>{{ ucfirst($otp->status) }}</td>
                        <td>{{ ucfirst($otp->purpose) }}</td>
                        <td>{{ $otp->created_at }}</td>
                        <td>{{ $otp->expires_at }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.otp.resend', $otp->id) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-teal-600 text-white rounded hover:bg-teal-700">Resend</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $otps->links() }}</div>
        </div>
    </div>
</div>
@endsection
