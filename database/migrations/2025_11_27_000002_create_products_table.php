<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('product_type');
            $table->string('category');
            $table->string('name');
            $table->string('manufacturer')->nullable();
            $table->string('model_number')->nullable();
            $table->string('condition')->nullable();
            $table->string('age_of_equipment')->nullable();
            $table->date('last_serviced_date')->nullable();
            $table->boolean('known_issues')->nullable();
            $table->text('known_issues_details')->nullable();
            $table->text('accessories')->nullable();
            $table->date('pickup_available_date')->nullable();
            $table->string('equipment_location')->nullable();
            $table->string('shipping_cost_contribution')->nullable();
            $table->text('address')->nullable();
            $table->string('donor_type')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('phone_number')->nullable();
            $table->json('photos')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};