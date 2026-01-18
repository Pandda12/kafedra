<?php

declare(strict_types=1);

use App\Models\{File, Task, User};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_user', function ( Blueprint $table ) {
            $table->id();
            $table->foreignIdFor(Task::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('status', 24);
            $table->date('started_at')->nullable();
            $table->date('rejected_at')->nullable();
            $table->unsignedInteger('number_of_rejections')->nullable();
            $table->text('reject_reason')->nullable();
            $table->date('completed_at')->nullable();
            $table->text('report_text')->nullable();
            $table->foreignId('report_file_id')->nullable()->constrained('files')->nullOnDelete();
            $table->timestampTz('reported_at')->nullable();
            $table->timestamps();

            $table->unique(['task_id', 'user_id']);
            $table->index(['user_id', 'status']);
            $table->index(['task_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_user');
    }
};
