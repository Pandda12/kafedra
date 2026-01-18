<?php

declare(strict_types=1);

use App\Models\{AcademicYear, File, PublicationType, User};
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
        Schema::create('publications', function ( Blueprint $table ) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(AcademicYear::class)->constrained()->cascadeOnDelete();
            $table->string('author');
            $table->string('co_author')->nullable();
            $table->foreignIdFor(PublicationType::class)->nullable()->constrained()->nullOnDelete();
            $table->string('name_of_scientific_event');
            $table->string('publisher');
            $table->unsignedInteger('year');
            $table->string('pages');
            $table->string('DOI_url')->nullable();
            $table->string('bibliographic_description');
            $table->string('repository_url');
            $table->foreignIdFor(File::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
