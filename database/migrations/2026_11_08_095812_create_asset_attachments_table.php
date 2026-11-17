
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('asset_id')
                ->constrained('assets')
                ->cascadeOnDelete();

            $table->string('file_name', 255);
            $table->string('file_path', 255);
            $table->string('file_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->string('attachment_type', 50)->default('other');
            $table->text('description')->nullable();

            $table->foreignUuid('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_attachments');
    }
};
