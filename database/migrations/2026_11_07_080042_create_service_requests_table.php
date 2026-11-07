
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('ticket_id')
                ->unique()
                ->constrained('tickets')
                ->restrictOnDelete();

            $table->string('request_type', 50);

            $table->string('item_name', 150)->nullable();

            $table->unsignedSmallInteger('quantity')->nullable();

            $table->date('needed_at')->nullable();

            $table->text('justification')->nullable();

            $table->json('details')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
