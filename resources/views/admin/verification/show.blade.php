@extends('layouts.dashboard')

@section('title', 'Review Verification')
@section('page-title', 'Verification Review')

@section('content')
<div style="margin-bottom: 30px;">
    <a href="{{ route('admin.verification.index') }}" style="color: #667eea; text-decoration: none; font-weight: 500;">
        <i class="fas fa-arrow-left"></i> Back to Verification List
    </a>
</div>

@if(session('success'))
<div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- User Information -->
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px;">
    <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 20px;">User Information</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div>
            <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Name</div>
            <div style="font-weight: 600; color: #2c3e50;">{{ $user->name }}</div>
        </div>
        <div>
            <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Email</div>
            <div style="font-weight: 600; color: #2c3e50;">{{ $user->email }}</div>
        </div>
        <div>
            <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Mobile Number</div>
            <div style="font-weight: 600; color: #2c3e50;">{{ $user->mobile_number }}</div>
        </div>
        <div>
            <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Account Type</div>
            <div>
                <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #e0e7ff; color: #3730a3;">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>
        <div>
            <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Verification Status</div>
            <div>
                <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: {{ $user->getVerificationBadgeColor() }}20; color: {{ $user->getVerificationBadgeColor() }};">
                    {{ $user->getVerificationStatusLabel() }}
                </span>
            </div>
        </div>
        <div>
            <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Registered</div>
            <div style="font-weight: 600; color: #2c3e50;">{{ $user->created_at->format('M d, Y') }}</div>
        </div>
    </div>

    @if($user->verification_submitted_at)
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Submitted for Review</div>
        <div style="font-weight: 600; color: #2c3e50;">{{ $user->verification_submitted_at->format('M d, Y h:i A') }}</div>
    </div>
    @endif

    @if($user->verification_reviewed_at)
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Reviewed</div>
        <div style="font-weight: 600; color: #2c3e50;">
            {{ $user->verification_reviewed_at->format('M d, Y h:i A') }}
            @if($user->verificationReviewer)
            by {{ $user->verificationReviewer->name }}
            @endif
        </div>
    </div>
    @endif

    @if($user->rejection_reason)
    <div style="margin-top: 20px; padding: 16px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px;">
        <div style="font-size: 14px; font-weight: 600; color: #991b1b; margin-bottom: 8px;">Rejection Reason:</div>
        <div style="color: #991b1b;">{{ $user->rejection_reason }}</div>
    </div>
    @endif
</div>

<!-- Verification Documents -->
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px;">
    <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 20px;">Verification Documents</h3>

    @php
        $requiredDocs = $user->getRequiredDocumentTypes();
        $uploadedDocs = $user->verificationDocuments->keyBy('document_type');
    @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        @foreach($requiredDocs as $docType)
        @php
            $document = $uploadedDocs->get($docType);
            $docNames = [
                'nid_front' => 'NID Card (Front)',
                'nid_back' => 'NID Card (Back)',
                'trade_license' => 'Trade License',
                'personal_photo' => 'Personal Photo',
                'shop_profile' => 'Shop Profile Picture',
            ];
            $docName = $docNames[$docType] ?? ucfirst(str_replace('_', ' ', $docType));
        @endphp

        <div style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 20px;">
            <h4 style="font-size: 16px; color: #2c3e50; margin-bottom: 12px;">{{ $docName }}</h4>

            @if($document)
            <div style="text-align: center;">
                <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $docName }}" 
                    style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 12px; cursor: pointer; border: 2px solid #ddd;"
                    onclick="window.open('{{ asset('storage/' . $document->file_path) }}', '_blank')">
                
                <div style="text-align: left; font-size: 13px; color: #7f8c8d;">
                    <div><strong>Size:</strong> {{ $document->formatted_file_size }}</div>
                    <div><strong>Uploaded:</strong> {{ $document->created_at->format('M d, Y') }}</div>
                </div>

                <div style="margin-top: 12px;">
                    <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #10b981; color: white;">
                        <i class="fas fa-check"></i> Uploaded
                    </span>
                </div>
            </div>
            @else
            <div style="text-align: center; padding: 40px 20px;">
                <i class="fas fa-times-circle" style="font-size: 48px; color: #ef4444; margin-bottom: 12px;"></i>
                <div style="color: #ef4444; font-weight: 600;">Not Uploaded</div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

<!-- Actions -->
@if($user->verification_status === 'pending' || $user->verification_status === 'unverified')
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px;">
    <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 20px;">Review Actions</h3>

    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
        <!-- Approve Button -->
        <form method="POST" action="{{ route('admin.verification.approve', $user->id) }}" style="flex: 1; min-width: 200px;">
            @csrf
            <button type="submit" onclick="return confirm('Approve this verification?')"
                style="width: 100%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 16px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px;">
                <i class="fas fa-check-circle"></i> Approve Verification
            </button>
        </form>

        <!-- Reject Button -->
        <button onclick="showRejectModal()" 
            style="flex: 1; min-width: 200px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 16px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px;">
            <i class="fas fa-times-circle"></i> Reject Verification
        </button>
    </div>
</div>
@endif

@if($user->verification_status === 'verified' || $user->verification_status === 'rejected')
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px;">
    <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 20px;">Additional Actions</h3>

    <form method="POST" action="{{ route('admin.verification.reset', $user->id) }}" onsubmit="return confirm('Reset verification? This will delete all documents and allow the user to resubmit.');">
        @csrf
        <button type="submit" 
            style="background: #f59e0b; color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
            <i class="fas fa-redo"></i> Reset Verification
        </button>
    </form>
</div>
@endif

<!-- Reject Modal -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; width: 90%; max-width: 500px; padding: 24px;">
        <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 16px;">Reject Verification</h3>
        
        <form method="POST" action="{{ route('admin.verification.reject', $user->id) }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Rejection Reason *</label>
                <textarea name="rejection_reason" required rows="4" 
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                    placeholder="Explain why the verification is being rejected..."></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeRejectModal()" 
                    style="padding: 12px 24px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" 
                    style="padding: 12px 24px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                    <i class="fas fa-times-circle"></i> Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

// Close modal on outside click
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection
