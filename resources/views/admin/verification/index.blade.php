@extends('layouts.dashboard')

@section('title', 'Verification Management')
@section('page-title', 'Verification')

@section('content')
<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Verification Management</h2>
            <p style="color: #7f8c8d;">Review and approve vendor verification requests</p>
        </div>
    </div>
</div>

@if(session('success'))
<div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div style="background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Pending Review</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['pending'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Verified</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['verified'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Rejected</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['rejected'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Unverified</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['unverified'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Vendors</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['total'] }}</div>
    </div>
</div>

<!-- Filters -->
<div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.verification.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, Phone..."
                style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
        </div>
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Status</label>
            <select name="status" style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Role</label>
            <select name="role" style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="">All Roles</option>
                <option value="retailer" {{ request('role') == 'retailer' ? 'selected' : '' }}>Retailer</option>
                <option value="wholesaler" {{ request('role') == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                <option value="exporter" {{ request('role') == 'exporter' ? 'selected' : '' }}>Importer</option>
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" style="flex: 1; background: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.verification.index') }}" style="background: #e5e7eb; color: #6b7280; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">User</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Role</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Status</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Documents</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Submitted</th>
                    <th style="padding: 16px; text-align: center; font-size: 14px; font-weight: 600; color: #2c3e50;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 16px;">
                        <div style="font-weight: 600; color: #2c3e50;">{{ $user->name }}</div>
                        <div style="font-size: 13px; color: #7f8c8d;">{{ $user->email }}</div>
                        <div style="font-size: 13px; color: #7f8c8d;">{{ $user->mobile_number }}</div>
                    </td>
                    <td style="padding: 16px;">
                        <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #e0e7ff; color: #3730a3;">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td style="padding: 16px;">
                        <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: {{ $user->getVerificationBadgeColor() }}20; color: {{ $user->getVerificationBadgeColor() }};">
                            {{ $user->getVerificationStatusLabel() }}
                        </span>
                    </td>
                    <td style="padding: 16px;">
                        <div style="font-weight: 600; color: #2c3e50;">
                            {{ $user->verificationDocuments->count() }} / {{ count($user->getRequiredDocumentTypes()) }}
                        </div>
                        <div style="font-size: 12px; color: #7f8c8d;">documents uploaded</div>
                    </td>
                    <td style="padding: 16px;">
                        @if($user->verification_submitted_at)
                        <div style="font-size: 13px; color: #2c3e50;">{{ $user->verification_submitted_at->format('M d, Y') }}</div>
                        <div style="font-size: 12px; color: #7f8c8d;">{{ $user->verification_submitted_at->format('h:i A') }}</div>
                        @else
                        <span style="color: #9ca3af;">Not submitted</span>
                        @endif
                    </td>
                    <td style="padding: 16px; text-align: center;">
                        <a href="{{ route('admin.verification.show', $user->id) }}" 
                            style="background: #667eea; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; display: inline-block;">
                            <i class="fas fa-eye"></i> Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 60px 20px; text-align: center;">
                        <i class="fas fa-user-check" style="font-size: 64px; color: #d1d5db; margin-bottom: 16px;"></i>
                        <div style="font-size: 18px; color: #6b7280; margin-bottom: 8px;">No Verification Requests</div>
                        <div style="font-size: 14px; color: #9ca3af;">Vendor verification requests will appear here</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding: 20px; border-top: 1px solid #e5e7eb;">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
