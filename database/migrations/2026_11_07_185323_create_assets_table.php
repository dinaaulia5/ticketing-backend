<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('asset_code', 50)->unique();
            $table->string('name', 150);
            $table->foreignUuid('asset_category_id')->constrained('asset_categories')->restrictOnDelete();
            // Asset Information
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 150)->nullable()->unique();
            // Technical Specifications
            $table->string('processor', 150)->nullable();
            $table->string('ram', 50)->nullable();
            $table->string('storage', 100)->nullable();
            $table->string('os', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('mac_address', 50)->nullable();
            // Asset Location
            $table->foreignUuid('location_id')->nullable()->constrained('locations')->nullOnDelete();
            // Purchase Information
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->date('end_of_life')->nullable();
            // Asset Condition & Status
            $table->string('condition', 30)->default('good');
            $table->string('status', 30)->default('available');
            // Additional Information
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
