<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('cnpj', 14)->unique();
            $table->string('address');
            $table->string('zipcode', 9);
            $table->string('district', 100)->index();
            $table->decimal('latitude', 10, 8)->index();
            $table->decimal('longitude', 11, 8)->index();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->string('logo')->nullable();
            $table->string('whatsapp', 11)->unique()->index();
            $table->integer('simultaneous_services')->default(1);
            $table->decimal('rating_avg', 2, 1)->nullable();
            $table->integer('rating_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('company_user', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 20);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_user');
        Schema::dropIfExists('companies');
    }
};
