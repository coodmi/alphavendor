@extends('layouts.app')

@section('title', 'Retailer Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Retailer Dashboard</h1>
        <p>Welcome back, {{ Auth::user()->name }}!</p>
    </div>

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-info">
                <h3>0</h3>
                <p>Total Products</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-info">
                <h3>0</h3>
                <p>Orders</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>$0</h3>
                <p>Revenue</p>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="dashboard-section">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="#" class="btn btn-primary">Add Product</a>
                <a href="#" class="btn btn-success">View Orders</a>
                <a href="#" class="btn btn-info">Manage Inventory</a>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>Recent Activity</h2>
            <p>No recent activity yet. Start by adding your first product!</p>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h1 {
    color: #333;
    margin-bottom: 10px;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    font-size: 40px;
}

.stat-info h3 {
    font-size: 32px;
    margin: 0;
    color: #007bff;
}

.stat-info p {
    margin: 5px 0 0;
    color: #666;
}

.dashboard-content {
    display: grid;
    gap: 30px;
}

.dashboard-section {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.dashboard-section h2 {
    margin-bottom: 20px;
    color: #333;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
    display: inline-block;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-info:hover {
    background: #138496;
}
</style>
@endsection
