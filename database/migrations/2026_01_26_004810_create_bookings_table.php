<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->date('scheduled_date');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('status', 20)->default('pending');
            $table->integer('price');
            $table->string('payment_status', 20)->default('pending');
            $table->string('payment_method', 20)->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->mediumText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
