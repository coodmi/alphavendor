<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Sales Manager", "Inventory Supervisor"
            $table->string('slug')->unique(); // e.g., "sales-manager", "inventory-supervisor"
            $table->text('description')->nullable();
            $table->json('permissions')->nullable(); // Store permissions as JSON
            $table->enum('access_level', ['basic', 'extended', 'full'])->default('basic');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Insert default employee roles
        DB::table('employee_roles')->insert([
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Basic employee with standard access',
                'permissions' => json_encode(['view_dashboard', 'view_orders', 'view_products']),
                'access_level' => 'basic',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manager with extended access and team oversight',
                'permissions' => json_encode(['view_dashboard', 'view_orders', 'view_products', 'manage_orders', 'view_reports', 'manage_team']),
                'access_level' => 'extended',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor',
                'slug' => 'supervisor',
                'description' => 'Supervisor with full access to all employee features',
                'permissions' => json_encode(['view_dashboard', 'view_orders', 'view_products', 'manage_orders', 'view_reports', 'manage_team', 'manage_inventory', 'view_analytics', 'manage_customers']),
                'access_level' => 'full',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_roles');
    }
};
