@extends('layouts.dashboard')

@section('title', 'Returns & Refunds Management')
@section('page-title', 'Returns & Refunds')

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
    @elseif($userRole === 'admin')
        @include('dashboards.partials.admin-sidebar')
    @elseif($userRole === 'employee')
        @include('dashboards.partials.employee-sidebar')
    @endif
@endsection

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #7f8c8d;
        font-size: 14px;
        font-weight: 600;
    }
    
    .filters-bar {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .filter-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-input {
        padding: 10px 15px;
        border: 2px solid #e8e8e8;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: #667eea;
    }
    
    .btn-primary {
        padding: 10px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        padding: 10px 20px;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }
    
    .returns-table {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .table-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 2px solid #e8e8e8;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .badge-approved {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-rejected {
        background: #f8d7da;
        color: #721c24;
    }
    
    .badge-completed {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .type-badge {
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
    }
    
    .badge-return {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .badge-refund {
        background: #fff3e0;
        color: #f57c00;
    }
    
    .badge-exchange {
        background: #f3e5f5;
        color: #7b1fa2;
    }
    
    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-view {
        background: #667eea;
        color: white;
    }
    
    .btn-view:hover {
        background: #5568d3;
    }
</style>

@if(session('success'))
    <div style="background: #d4edda; border: 2px solid #c3e6cb; color: #155724; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #f8d7da; border: 2px solid #f5c6cb; color: #721c24; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <i class="fas fa-undo"></i>
        </div>
        <div class="stat-value" style="color: #667eea;">{{ $stats['total'] }}</div>
        <div class="stat-label">Total Returns</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fff3cd; color: #856404;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value" style="color: #f39c12;">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending Review</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #d4edda; color: #155724;">
            <i class="fas fa-check"></i>
        </div>
        <div class="stat-value" style="color: #2ecc71;">{{ $stats['approved'] }}</div>
        <div class="stat-label">Approved</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #f8d7da; color: #721c24;">
            <i class="fas fa-times"></i>
        </div>
        <div class="stat-value" style="color: #e74c3c;">{{ $stats['rejected'] }}</div>
        <div class="stat-label">Rejected</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #d1ecf1; color: #0c5460;">
            <i class="fas fa-check-double"></i>
        </div>
        <div class="stat-value" style="color: #17a2b8;">{{ $stats['completed'] }}</div>
        <div class="stat-label">Completed</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-value" style="color: #27ae60; font-size: 24px;"> {{ currency($stats['total_amount']) }}</div>
        <div class="stat-label">Total Refund Amount</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <form method="GET" action="{{ route('vendor.returns.index') }}">
        <div class="filter-row">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Search by return #, customer, or product..." 
                   class="filter-input" 
                   style="flex: 1; min-width: 250px;">
            
            <select name="status" class="filter-input" style="width: 150px;">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
            
            <select name="type" class="filter-input" style="width: 150px;">
                <option value="">All Types</option>
                <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Return</option>
                <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                <option value="exchange" {{ request('type') == 'exchange' ? 'selected' : '' }}>Exchange</option>
            </select>
            
            <button type="submit" class="btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>
            
            <a href="{{ route('vendor.returns.index') }}" class="btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Returns Table -->
<div class="returns-table">
    <div class="table-header">
        <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #2c3e50;">
            <i class="fas fa-list"></i> Return Requests ({{ $returns->total() }})
        </h3>
        <div>
            <button onclick="toggleBulkActions()" class="btn-secondary" style="padding: 8px 16px; font-size: 13px;">
                <i class="fas fa-tasks"></i> Bulk Actions
            </button>
        </div>
    </div>
    
    <!-- Bulk Actions Bar (Hidden by default) -->
    <div id="bulkActionsBar" style="display: none; background: #f8f9fa; padding: 15px 20px; border-bottom: 2px solid #e8e8e8;">
        <form method="POST" action="{{ route('vendor.returns.bulk-action') }}" onsubmit="return confirm('Are you sure you want to perform this action on selected returns?')">
            @csrf
            <div style="display: flex; gap: 15px; align-items: center;">
                <span style="font-weight: 600; color: #2c3e50;">
                    <span id="selectedCount">0</span> selected
                </span>
                <select name="action" required class="filter-input" style="width: 150px;">
                    <option value="">Select Action</option>
                    <option value="approve">Approve</option>
                    <option value="reject">Reject</option>
                    <option value="complete">Mark Complete</option>
                </select>
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px;">
                    Apply
                </button>
                <button type="button" onclick="toggleBulkActions()" class="btn-secondary" style="padding: 8px 16px; font-size: 13px;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">
                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                    </th>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Return #</th>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Customer</th>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Product</th>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Type</th>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Amount</th>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Status</th>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Date</th>
                    <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $return)
                    <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                        <td style="padding: 15px 20px;">
                            <input type="checkbox" name="return_ids[]" value="{{ $return->id }}" class="return-checkbox" onchange="updateSelectedCount()">
                        </td>
                        <td style="padding: 15px 20px;">
                            <span style="font-weight: 700; color: #2c3e50;">{{ $return->return_number }}</span>
                        </td>
                        <td style="padding: 15px 20px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                    {{ strtoupper(substr($return->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #2c3e50;">{{ $return->user->name }}</div>
                                    <div style="font-size: 12px; color: #7f8c8d;">{{ $return->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px 20px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($return->product->image)
                                    <img src="{{ asset('storage/' . $return->product->image) }}" 
                                         alt="{{ $return->product->name }}" 
                                         style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                @else
                                    <div style="width: 50px; height: 50px; border-radius: 8px; background: #e8e8e8; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-box" style="color: #999;"></i>
                                    </div>
                                @endif
                                <span style="color: #2c3e50; font-weight: 500;">{{ Str::limit($return->product->name, 30) }}</span>
                            </div>
                        </td>
                        <td style="padding: 15px 20px;">
                            <span class="type-badge badge-{{ $return->type }}">
                                {{ ucfirst($return->type) }}
                            </span>
                        </td>
                        <td style="padding: 15px 20px;">
                            <span style="font-weight: 700; color: #2c3e50; font-size: 16px;"> {{ currency($return->refund_amount ?? 0) }}</span>
                        </td>
                        <td style="padding: 15px 20px;">
                            <span class="status-badge badge-{{ $return->status }}">
                                {{ ucfirst(str_replace('_', ' ', $return->status)) }}
                            </span>
                        </td>
                        <td style="padding: 15px 20px;">
                            <div style="color: #2c3e50; font-weight: 500;">{{ $return->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 12px; color: #7f8c8d;">{{ $return->created_at->format('h:i A') }}</div>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;">
                            <a href="{{ route('vendor.returns.show', $return) }}" class="action-btn btn-view">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding: 60px 20px; text-align: center;">
                            <i class="fas fa-undo" style="font-size: 64px; color: #ddd; margin-bottom: 15px;"></i>
                            <p style="color: #7f8c8d; font-size: 18px; margin: 0;">No return requests found</p>
                            <p style="color: #95a5a6; font-size: 14px; margin-top: 5px;">
                                @if(request()->has('status') || request()->has('type') || request()->has('search'))
                                    Try adjusting your filters
                                @else
                                    Return requests will appear here
                                @endif
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($returns->hasPages())
        <div style="padding: 20px; border-top: 2px solid #f0f0f0;">
            {{ $returns->links() }}
        </div>
    @endif
</div>

<script>
function toggleBulkActions() {
    const bar = document.getElementById('bulkActionsBar');
    bar.style.display = bar.style.display === 'none' ? 'block' : 'none';
    
    // Uncheck all if hiding
    if (bar.style.display === 'none') {
        document.getElementById('selectAll').checked = false;
        document.querySelectorAll('.return-checkbox').forEach(cb => cb.checked = false);
        updateSelectedCount();
    }
}

function toggleSelectAll(checkbox) {
    document.querySelectorAll('.return-checkbox').forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.return-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count;
    
    // Update form with selected IDs
    const form = document.querySelector('#bulkActionsBar form');
    const existingInputs = form.querySelectorAll('input[name="return_ids[]"]');
    existingInputs.forEach(input => input.remove());
    
    document.querySelectorAll('.return-checkbox:checked').forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'return_ids[]';
        input.value = cb.value;
        form.appendChild(input);
    });
}
</script>

@endsection
