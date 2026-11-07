
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ticket_number', 100)->unique();
            $table->foreignUuid('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();
            $table->string('ticket_type', 30);
            $table->foreignUuid('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignUuid('subsystem_id')->nullable()->constrained('subsystems')->restrictOnDelete();
            $table->string('title', 150);
            $table->text('description');
            $table->string('impact', 20)->nullable();
            $table->string('urgency', 20)->nullable();
            $table->string('priority', 20)->nullable();
            $table->string('status', 30)->default('open');
            $table->string('source', 20)->default('web');
            $table->timestamp('closed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
