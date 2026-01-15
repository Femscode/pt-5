<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_biddings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->string('request_code')->unique();
            $table->string('applicant_type')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('organization_website')->nullable();
            $table->string('facility_address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('equipment_name')->nullable();
            $table->string('urgency')->nullable();
            $table->string('preferred_manufacturer')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->string('can_contribute')->nullable();
            $table->string('budget')->nullable();
            $table->text('statement_of_need')->nullable();
            $table->text('intended_use')->nullable();
            $table->boolean('agreed')->default(false);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_biddings');
    }
};

