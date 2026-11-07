
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ticket_id')->constrained('tickets')->restrictOnDelete();
            $table->foreignUuid('technician_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('assigned_by')->constrained('users')->restrictOnDelete();
            $table->string('assignment_role', 20)->default('supporting');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('unassigned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['ticket_id', 'unassigned_at']);
            $table->index('technician_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_assignments');
    }
};
