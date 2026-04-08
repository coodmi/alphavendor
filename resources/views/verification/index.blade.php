@extends('layouts.dashboard')

@section('title', 'Account Verification')
@section('page-title', 'Verification')

@section('sidebar-menu')
    @php
        $userRole = auth()->user()->role;
    @endphp

    @if($userRole === 'retailer')
        @include('dashboards.partials.retailer-sidebar')
    @elseif($userRole === 'wholesaler')
        @include('dashboards.partials.wholesaler-sidebar')
    @elseif($userRole === 'exporter')
        @include('dashboards.partials.exporter-sidebar')
    @elseif($userRole === 'importer')
        @include('dashboards.partials.importer-sidebar')
    @endif
@endsection

@section('content')
<div style="margin-bottom: 30px;">
    <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Account Verification</h2>
    <p style="color: #7f8c8d;">Upload required documents to verify your account</p>
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

<!-- Verification Status Card -->
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 8px;">Verification Status</h3>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="padding: 8px 16px; border-radius: 20px; font-weight: 600; background: {{ auth()->user()->getVerificationBadgeColor() }}20; color: {{ auth()->user()->getVerificationBadgeColor() }};">
                    {{ auth()->user()->getVerificationStatusLabel() }}
                </span>
                @if(auth()->user()->verification_submitted_at)
                <span style="color: #7f8c8d; font-size: 14px;">
                    Submitted: {{ auth()->user()->verification_submitted_at->format('M d, Y h:i A') }}
                </span>
                @endif
            </div>
        </div>
        @if(auth()->user()->verification_status === 'unverified' && auth()->user()->hasAllRequiredDocuments())
        <form method="POST" action="{{ route('verification.submit') }}">
            @csrf
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                <i class="fas fa-paper-plane"></i> Submit for Review
            </button>
        </form>
        @endif
    </div>

    @if(auth()->user()->verification_status === 'pending')
    <div style="background: #fef3c7; border: 1px solid #f59e0b; padding: 16px; border-radius: 8px; margin-top: 16px;">
        <i class="fas fa-clock" style="color: #f59e0b;"></i>
        <strong style="color: #92400e;">Your account is under verification.</strong>
        <p style="color: #92400e; margin: 8px 0 0 0;">Please wait for admin approval. You will be notified once your account is verified.</p>
    </div>
    @endif

    @if(auth()->user()->verification_status === 'rejected' && auth()->user()->rejection_reason)
    <div style="background: #fee2e2; border: 1px solid #ef4444; padding: 16px; border-radius: 8px; margin-top: 16px;">
        <i class="fas fa-times-circle" style="color: #ef4444;"></i>
        <strong style="color: #991b1b;">Verification Rejected</strong>
        <p style="color: #991b1b; margin: 8px 0 0 0;"><strong>Reason:</strong> {{ auth()->user()->rejection_reason }}</p>
        <p style="color: #991b1b; margin: 8px 0 0 0; font-size: 14px;">Please update your documents and resubmit.</p>
    </div>
    @endif

    @if(auth()->user()->verification_status === 'verified')
    <div style="background: #d1fae5; border: 1px solid #10b981; padding: 16px; border-radius: 8px; margin-top: 16px;">
        <i class="fas fa-check-circle" style="color: #10b981;"></i>
        <strong style="color: #065f46;">Account Verified!</strong>
        <p style="color: #065f46; margin: 8px 0 0 0;">Your account has been successfully verified. You can now access all features.</p>
        @if(auth()->user()->verification_reviewed_at)
        <p style="color: #065f46; margin: 8px 0 0 0; font-size: 14px;">
            Verified on: {{ auth()->user()->verification_reviewed_at->format('M d, Y h:i A') }}
        </p>
        @endif
    </div>
    @endif
</div>

<!-- Required Documents -->
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px;">
    <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 20px;">Required Documents</h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        @foreach($requiredDocuments as $docType)
        @php
            $document = $documents->get($docType);
            $docNames = [
                'nid_front' => 'NID Card (Front)',
                'nid_back' => 'NID Card (Back)',
                'trade_license' => 'Trade License',
                'personal_photo' => 'Personal Photo',
                'shop_profile' => 'Shop Profile Picture',
            ];
            $docName = $docNames[$docType] ?? ucfirst(str_replace('_', ' ', $docType));
        @endphp

        <div style="border: 2px dashed #ddd; border-radius: 12px; padding: 20px; text-align: center; position: relative;">
            <div style="font-size: 48px; color: #d1d5db; margin-bottom: 12px;">
                <i class="fas fa-{{ $docType === 'personal_photo' || $docType === 'shop_profile' ? 'image' : 'file-alt' }}"></i>
            </div>
            <h4 style="font-size: 16px; color: #2c3e50; margin-bottom: 8px;">{{ $docName }}</h4>

            @if($document)
            <div style="margin-top: 12px;">
                <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $docName }}" 
                    style="max-width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 12px; cursor: pointer;"
                    onclick="window.open('{{ asset('storage/' . $document->file_path) }}', '_blank')">
                
                <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 8px;">
                    <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #10b981; color: white;">
                        <i class="fas fa-check"></i> Uploaded
                    </span>
                </div>

                @if(auth()->user()->verification_status !== 'pending')
                <button onclick="deleteDocument({{ $document->id }})" 
                    style="background: #ef4444; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-trash"></i> Delete
                </button>
                @endif
            </div>
            @else
            <div style="margin-top: 12px;">
                <input type="file" id="file_{{ $docType }}" accept="image/*" style="display: none;" 
                    onchange="uploadDocument('{{ $docType }}', this)">
                <label for="file_{{ $docType }}" 
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border-radius: 8px; cursor: pointer; display: inline-block; font-weight: 500;">
                    <i class="fas fa-upload"></i> Upload
                </label>
                <p style="color: #7f8c8d; font-size: 12px; margin-top: 8px;">Max 5MB, JPG/PNG</p>
            </div>
            @endif

            <div id="progress_{{ $docType }}" style="display: none; margin-top: 12px;">
                <div style="background: #e5e7eb; border-radius: 8px; height: 8px; overflow: hidden;">
                    <div id="progress_bar_{{ $docType }}" style="background: #667eea; height: 100%; width: 0%; transition: width 0.3s;"></div>
                </div>
                <p style="color: #7f8c8d; font-size: 12px; margin-top: 4px;">Uploading...</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
function uploadDocument(docType, input) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const formData = new FormData();
    formData.append('document', file);
    formData.append('document_type', docType);
    formData.append('_token', '{{ csrf_token() }}');

    // Show progress
    document.getElementById('progress_' + docType).style.display = 'block';

    fetch('{{ route('verification.upload') }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Upload failed');
            document.getElementById('progress_' + docType).style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Upload failed. Please try again.');
        document.getElementById('progress_' + docType).style.display = 'none';
    });
}

function deleteDocument(documentId) {
    if (!confirm('Are you sure you want to delete this document?')) return;

    fetch(`/verification/documents/${documentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Delete failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Delete failed. Please try again.');
    });
}
</script>
@endsection
